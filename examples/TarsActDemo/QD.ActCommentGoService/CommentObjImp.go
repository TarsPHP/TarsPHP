package main

import (
	"QD"
)

type CommentObjImp struct {
	service CommentService
}

func (imp *CommentObjImp) Ping() (ret int32, err error) {
    return 1, nil
}

func (imp *CommentObjImp) GetCount(Count *int32) (err error) {
	var test = int32(10)
	Count = &test
    return nil
}

func (imp *CommentObjImp) CreateComment(InParam *QD.CommonInParam, Comment *QD.SimpleComment, OutParam *QD.CommonOutParam) (err error) {
	err = imp.service.CreateComment(InParam, Comment)
	if err != nil {
		return err
	}

	OutParam.Code = 0
	OutParam.Message = "success"
	return nil
}

func (imp *CommentObjImp) GetComment(InParam *QD.CommonInParam, QueryParam *QD.QueryParam, OutParam *QD.CommonOutParam, List *[]QD.SimpleComment) (err error) {
	myList, err := imp.service.GetComment(InParam, QueryParam)
	if err != nil {
		return err
	}

	*List = myList

	OutParam.Code = 0
	OutParam.Message = "success"
	return nil
}

