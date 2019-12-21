package main

import (
	"QD"
	"encoding/json"
	"fmt"
	"github.com/TarsCloud/TarsGo/tars"
	"github.com/go-redis/redis"
	"reflect"
	"strconv"
	"time"
)

type CommentService struct {
}

func (imp CommentService) CreateComment(InParam *QD.CommonInParam, Comment *QD.SimpleComment) (err error) {
	redisClient, err := imp.getClient()
	if err != nil {
		return err
	}

	id, err := redisClient.IncrBy(imp.getCommentIdKey(), 1).Result()

	Comment.CreateTime = int64(time.Now().Nanosecond())
	Comment.Id = int32(id)
	if Comment.UserId == 0 {
		Comment.UserId = InParam.UserId
	}
	redisClient.HMSet(imp.getCommentContext(id), imp.objToArrayForTars(Comment))

	redisClient.RPush(imp.getIndexKey(Comment.ActivityId), id)

	return nil
}

func (imp CommentService) GetComment(InParam *QD.CommonInParam, QueryParam *QD.QueryParam) (List []QD.SimpleComment, err error) {
	redisClient, err := imp.getClient()
	if err != nil {
		return nil, err
	}
	indexKey := imp.getIndexKey(QueryParam.ActivityId)
	ids, err := redisClient.LRange(indexKey, int64((QueryParam.Page - 1) * QueryParam.Size), int64(QueryParam.Page * QueryParam.Size -1)).Result()

	pipe := redisClient.Pipeline()
	for _, id := range ids {
		pipe.HGetAll(imp.getCommentContextS(id))
	}
	cmds, err := pipe.Exec()
	if err != nil {
		fmt.Println("redis cmd error", err)
	}

	outList := make([]QD.SimpleComment, len(cmds))
	i := 0
	for _, cmd := range cmds {
		cmd := cmd.(*redis.StringStringMapCmd)
		strMap, err := cmd.Result()
		if err != nil {
			fmt.Println("err", err)
		}

		var out = new(QD.SimpleComment)
		id64, err := strconv.ParseInt(strMap["id"], 10, 32)
		out.Id = int32(id64)
		out.UserId, err = strconv.ParseInt(strMap["userId"], 10, 64)
		out.ActivityId, err = strconv.ParseInt(strMap["activityId"], 10, 64)
		out.Content = strMap["content"]
		out.Title = strMap["title"]
		out.CreateTime, err = strconv.ParseInt(strMap["createTime"], 10, 64)
		out.Ext1 = strMap["ext1"]

		fmt.Printf("key is %s \n", strMap["content"])

		outList[i] = *out
		i++
	}
	return outList, nil
}

func test() {
	confJson := "{\"ip\":\"127.0.0.1\", \"password\":\"test\", \"db\":\"0\"}"
	var confMap map[string]interface{}
	err := json.Unmarshal([]byte(confJson), &confMap)
	if err != nil {
		panic(err)
	}

	fmt.Printf(" type is %s", reflect.TypeOf(confMap["db"]).String())

	var dbInt int
	switch confMap["db"].(type) {
	case string:
		dbInt, _ = strconv.Atoi(confMap["db"].(string))
		break
	case int:
		dbInt = confMap["db"].(int)
		break
	case float64:
		dbInt = int(confMap["db"].(float64))
		break
	}

	fmt.Printf("db %s, pass %s, db %d\n", confMap["ip"], confMap["password"], dbInt)
}

func (imp CommentService) getClient() (client *redis.Client, err error) {
	cfg := tars.GetServerConfig()
	remoteConf := tars.NewRConf(cfg.App, cfg.Server, cfg.BasePath)
	confJson, _ := remoteConf.GetConfig("redisIp.conf")

	var confMap map[string]interface{}
	err = json.Unmarshal([]byte(confJson), &confMap)
	if err != nil {
		return nil, err
	}

	var dbInt int
	switch confMap["db"].(type) {
	case string:
		dbInt, _ = strconv.Atoi(confMap["db"].(string))
		break
	case int:
		dbInt = confMap["db"].(int)
		break
	case float64:
		dbInt = int(confMap["db"].(float64))
		break
	}

	client = redis.NewClient(&redis.Options{
		Addr:     confMap["ip"].(string),
		Password: confMap["password"].(string), // no password set
		DB:       dbInt,               // use default DB
	})

	pong, err := client.Ping().Result()
	if err != nil {
		fmt.Println(pong, err)
		return nil, err
	}
	return client, nil
}

func (imp CommentService) getCommentIdKey() string  {
	return "comment_id"
}

func (imp CommentService) getCommentContext(id int64) string  {
	ret := "comment_c_" + strconv.FormatInt(id,10)
	return ret
}

func (imp CommentService) getCommentContextS(id string) string  {
	ret := "comment_c_" + id
	return ret
}

func (imp CommentService) getIndexKey(activityId int64) string  {
	return "index_act_id_" + strconv.FormatInt(activityId,10)
}

func (imp CommentService) objToArrayForTars(Comment *QD.SimpleComment) map[string]interface{} {
	arr := make(map[string]interface{})
	arr["id"] = Comment.Id
	arr["activityId"] = Comment.ActivityId
	arr["userId"] = Comment.UserId
	arr["content"] = Comment.Content
	arr["title"] = Comment.Title
	arr["ext1"] = Comment.Ext1
	arr["createTime"] = Comment.CreateTime

	return arr
}