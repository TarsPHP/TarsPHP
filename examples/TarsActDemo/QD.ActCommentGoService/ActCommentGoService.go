package main

import (
	"github.com/TarsCloud/TarsGo/tars"

	"QD"
)

func main() { //Init servant
	imp := new(CommentObjImp)                                    //New Imp
	app := new(QD.CommentObj)                                 //New init the A Tars
	cfg := tars.GetServerConfig()                               //Get Config File Object
	app.AddServant(imp, cfg.App+"."+cfg.Server+".CommentObj") //Register Servant
	tars.Run()
}
