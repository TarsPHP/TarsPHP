# Practice of using tars-php



## Comprehensive information

-Source https://github.com/tenant/tars

-Currently supported development languages: C + +, Java, nodejs, PHP

-Detailed introduction and architecture https://github.com/tarscloud/tars/introduction.md

-Question exchange QQ group: 669339903


## Installation related

1. Before installation, please follow this page to prepare the software https://github.com/tarscloud/tars/tree/master/build

2. It is recommended to read the following file https://github.com/tarscloud/tars/blob/master/build/install.sh (in fact, some software depends on some scripts to install automatically, avoiding repeated work), and be familiar with the general script installation process

3. Refer to the first two parts, and then follow the installation instructions to install https://github.com/tarscloud/tars/blob/master/install.md

4. Note: please use server intranet IP instead of 127.0.0.1

5. For common problems and precautions during installation, please read https://github.com/tarscloud/tars/blob/master/install_faq.md

### Installation environment dependency



Software | software requirements | remarks

------|--------|--------

Linux kernel version: | 2.6.18 and above (OS dependent) | CentOS 6.9|

GCC version: | 4.8.2 and above, glibc devel (c + + language framework dependency)|

Bison tool version: | 2.5 and above (c + + framework dependent)|

Flex tool version: | 2.5 and above (c + + framework dependent)|

Cmake version: | 2.8.8 and above (c + + framework dependent)|

Resin version: | 4.0.49 and above (WEB management system dependency) | download settings soft chain|

Java JDK version: | Java language Framework (minimum 1.6), web management system (minimum 1.8) | errors may be reported during installation. Pay attention to environment variable settings|

Maven version: | 2.2.1 and above (WEB management system, Java language framework dependent)|

MySQL version: | 4.1.17 and above (framework running dependency) | pay attention to account and permission settings|

Rapidjson version: | 1.0.2 Version (c + + language framework depends on) | can be installed independently, and the installation process will download by itself|
### Install tars core services



1. Error correction:



If the files tar_install.sh and monitor.sh are not available after installation, you can copy them manually:

Copy from source / CPP / build / framework / deploy / tars_install.sh, CPP / build / framework / deploy / tarsnode / util / monitor.sh



3. Follow the instructions and order of the documents.




### Install web management interface



1. In the "2.2. Java language framework development environment installation" part of the document, to build a web engineering project, you can directly modify the app.config.properties and tar.conf configurations under the source web directory, and then directly pack them!



2. Run / usr / local / rein / bin / rein.sh start to start the web management interface. Normally, you will see the initialization interface. Three services are configured by default.



### Service start

```
service mysql start
/usr/local/app/tars/tars_install.sh
/usr/local/app/tars/tarspatch/util/init.sh
/usr/local/app/tars/tarsnode/bin/tarsnode --config=/usr/local/app/tars/tarsnode/conf/tarsnode.conf
/usr/local/resin/bin/resin.sh start

```

After normal startup, you will see the following process:
```
[root@centos data]# ps -ef|grep tars
root       5495      1  0 11:35 pts/0    00:00:03 /usr/local/app/tars/tarsregistry/bin/tarsregistry --config=/usr/local/app/tars/tarsregistry/conf/tarsregistry.conf
root       5504      1  0 11:35 pts/0    00:00:01 /usr/local/app/tars/tarsAdminRegistry/bin/tarsAdminRegistry --config=/usr/local/app/tars/tarsAdminRegistry/conf/adminregistry.conf
root       5514      1  0 11:35 pts/0    00:00:02 /usr/local/app/tars/tarsconfig/bin/tarsconfig --config=/usr/local/app/tars/tarsconfig/conf/tarsconfig.conf
root       5523      1  0 11:35 pts/0    00:00:01 /usr/local/app/tars/tarspatch/bin/tarspatch --config=/usr/local/app/tars/tarspatch/conf/tarspatch.conf
root       5610      1  0 11:35 ?        00:00:00 rsync --address=192.168.222.132 --daemon --config=/usr/local/app/tars/tarspatch/conf/rsync.conf
root       5621      1  2 11:35 ?        00:00:18 /usr/local/app/tars/tarsnode/bin/tarsnode --config=/usr/local/app/tars/tarsnode/conf/tarsnode.conf
root       6021   5621  0 11:36 ?        00:00:05 /usr/local/app/tars/tarsnode/data/tars.tarslog/bin/tarslog --config=/usr/local/app/tars/tarsnode/data/tars.tarslog/conf/tars.tarslog.config.conf
root       6022   5621  0 11:36 ?        00:00:05 /usr/local/app/tars/tarsnode/data/tars.tarsnotify/bin/tarsnotify --config=/usr/local/app/tars/tarsnode/data/tars.tarsnotify/conf/tars.tarsnotify.config.conf
root       6054   5621  0 11:36 ?        00:00:03 /usr/local/app/tars/tarsnode/data/tars.tarsqueryproperty/bin/tarsqueryproperty --config=/usr/local/app/tars/tarsnode/data/tars.tarsqueryproperty/conf/tars.tarsqueryproperty.config.conf
root       6108   5621  0 11:36 ?        00:00:03 /usr/local/app/tars/tarsnode/data/tars.tarsquerystat/bin/tarsquerystat --config=/usr/local/app/tars/tarsnode/data/tars.tarsquerystat/conf/tars.tarsquerystat.config.conf
root       6163   5621  0 11:37 ?        00:00:04 /usr/local/app/tars/tarsnode/data/tars.tarsstat/bin/tarsstat --config=/usr/local/app/tars/tarsnode/data/tars.tarsstat/conf/tars.tarsstat.config.conf
root       7097   5621  0 11:42 ?        00:00:01 /usr/local/app/tars/tarsnode/data/tars.tarsproperty/bin/tarsproperty --config=/usr/local/app/tars/tarsnode/data/tars.tarsproperty/conf/tars.tarsproperty.config.conf
```




### Install basic services



Package the basic package related to "4.1. Framework basic service packaging" in the installation document, and then configure, upload and publish through the management interface



When publishing, it needs to be configured in the "operation and maintenance management module" of web management. For details, please refer to the "4.4. Installation framework general basic services" section



Other people

- Php7 + swoole2 is recommended for PHP

- PHP needs to install the extension. Please compile it in the https://github.com/tarphp/tar-extension source directory, and then add the extension to php.ini





## Tars usage related



### Tars protocol

The tars protocol is implemented by the interface description language (IDL). It is a binary, extensible, automatic code generation and multi platform protocol, which enables objects running on different platforms and programs written in different languages to communicate with each other in the way of RPC remote call, It is mainly used in the network transmission protocol between background services, as well as object serialization and deserialization.



There are two types of protocol support: basic type and complex type.



The basic types include void, bool, byte, short, int, long, float, double, string, unsigned byte, unsigned short, and unsigned int;



Complex types include enum, const, struct, vector, map, and nesting of struct, vector, map.



### Development and packaging

There are differences in the development of different languages, but the basic ideas are the same:

1. Write the tar file xx.tar;

such as
```
module MTT
{
	enum MYE1
	{
		EM_CAT,
		EM_DOG,
	};
	struct A
	{
		0 require int b;
	};
    struct HelloWorld
    {
        0 require int nId;
        1 require string sMsg;
        2 require vector<string> vNews;
		///4 optional string sNew="client new";
		5 require map<int,int> mAddr;
		6 require A a;
    };

	struct HelloPerson
	{
		0 require int nId;
		1 require string sName;
		2 optional vector<string> vFavor;
		3 optional MYE1 ePet;
		///4 optional byte aPetAge[5];
		5 optional map<int,string> mAddr;
		6 optional string sPhone;
		7 optional bool bMan;
		9 optional HelloWorld shello;
	};

    interface Hello
    {
        int testHello(string s,vector<short> vsh,out string r);
		int testPerson(HelloPerson stPersonIn,out HelloWorld stPersonOut);
    };
};
```

2. Use the official transformation tool of tar to generate client or server code according to the contents of tar file (specify whether to generate client or server in the configuration file, pay attention to the naming specification);



3. Improve the code logic (cloze);

4. Packing code;

5. Add application information through the web management interface (consistent with the tars definition) and upload the final packaging code of the above steps and publish it.

6. Code package file upload location / data / tar / patches / tar.upload/





### Release

Code packaging also performs the publishing process and can restart or shut down processing in the service management list.



After the package program is uploaded, it will be placed in the directory / usr / local / APP / tars / tarsnode / data of the application server, -- application name + service name, service name phptest, and service name phphphttpserver will decompress the code and place it in the directory phptest.phphttpserver



The bin, conf, and data folders will be placed in each complete directory. The basic functions are as follows:

- Bin directory -- place the decompressed program file. At the same time, the tar automatically generates the corresponding start command file and close command files tars_start.sh and tars_stop.sh (you can also skip the web management interface and use this file to start / close the service directly)

- In the conf directory, the service template configuration files related to this application are stored -- the final configuration files generated by combining the configuration items filled in by the application publishing;

- The cache information file and process ID file of the service are placed in the data directory
### Log view



The log directory is located in / usr / local / APP / tar / app_log /, where the corresponding log files are found under the application name directory, such as / usr / local / APP / tar / app_log / phptest / phphphttpserver / phptest.phphttpserver.log



The log path of the service is under the application name / service service name / directory of / usr / local / APP / tars / APP μ log / service, for example, / usr / local / APP / tars / APP μ log / test / helloserver/



The executable file of the service is under the application name of / usr / local / APP / tar / tarsnode / data / service. The service name of the service is / bin /. For example, / usr / local / APP / tar / tarsnode / data / test.helloserver/bin/



The template configuration file of the service is under the application name of / usr / local / APP / tar / tarsnode / data / service. The service name of the service is / conf /. For example, / usr / local / APP / tar / tarsnode / data / test.helloserver/conf/



The cache information file of the service is under the application name of / usr / local / APP / tar / tarsnode / data / service. For example, / usr / local / APP / tar / tarsnode / data / test.helloserver/data/

## Tars PHP development related



### PHP service template configuration



When starting and running each tars service, a template configuration file must be specified. The template configuration of the services deployed in the tars web management system is generated by the node organization. If it is not on the web management system, a template file needs to be created by itself. Specific https://github.com/tencent/tas/blob/master/docs/tar'u template.md




For PHP development, first, in the web management interface - > operation and maintenance management - > template management, find the tar.tarsphp.default template, and modify it according to the actual PHP installation location, such as:

```
php=/usr/bin/php/bin/php

```



At the same time, copy the contents of tars.tarsphp.default, and create new templates of TCP, HTTP, timer versions

Compared with the HTTP template, the differences are as follows:



The HTTP template is added in the server node:

```
protocolName=http

Type=http
```



Add TCP template in server node:
```
package_length_type=N
open_length_check=1
package_length_offset=0
package_body_offset=0
package_max_length=2000000
protocolName=tars
type=tcp
```

The timer template is added in the server node:

```
protocolName=http

Type=http

IsTimer=1
```



Note:

- Select tar.default for all the parent template names

- The descriptions of protocolname and type in the document are missing, and errors will be reported in actual use. Please follow the above instructions for safety