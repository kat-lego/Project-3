# Welcome to the Project-3 Repo
**We are the Pipers**

[![Build Status](https://travis-ci.org/maniac22/Project-3.svg?branch=sprint3_dev)](https://travis-ci.org/maniac22/Project-3)
[![Coverage Status](https://coveralls.io/repos/github/maniac22/Project-3/badge.svg?branch=sprint3_dev)](https://coveralls.io/github/maniac22/Project-3?branch=sprint3_dev)

##  Abstract
This is a moodle plug in to be used for conducting Competitive Programming Assingments. The platform will be equiped with a number of different types of Assignments. The Platform consists of two parts.An automating marker(or handler) running off some remote server and the plugin on moodle.

## Prerequisites
#### For the Moodle Plugin
* [Moodle](https://docs.moodle.org/36/en/Installing_Moodle) version 3.6.
* Moodle must be using [MySql](https://tutorials.ubuntu.com/tutorial/install-and-configure-apache#0) as its database
#### For the Handler
* [Php](https://www.php.net/manual/en/install.php) 7
* Ubuntu Server 16.04 LTS (with sudo Privelages)
* [Apache](https://tutorials.ubuntu.com/tutorial/install-and-configure-apache#0) running on the server
* [MySql](https://tutorials.ubuntu.com/tutorial/install-and-configure-apache#0)

## Installation
To install our plugin, first you need to clone this repo either with git clone or the download option that is available on github.

* option 1(Recommended):
```
git clone https://github.com/maniac22/Project-3.git
```
* option 2:

![Download Example](https://github.com/maniac22/Project-3/blob/master/artifacts/img/example1.png)

Once you have cloned this repo, you can then zip it and [install the moodle plugin](https://docs.moodle.org/36/en/Installing_plugins). If you have downloaded the repo using the git download option, make sure you delete the .git folder inside the zip you have downloaded. Once you are done download the [marker](https://github.com/maniac22/PiedMarker2) and move it to an apache server.

## User Instructions
Once you have installed the plugin you will need to set up some settings for the plugin as shown below.


![Settings Example](https://github.com/maniac22/Project-3/blob/master/artifacts/img/example2.png)


Once the settings have been set, you can proceed to create an assignment and enable the plugin for the assignment and set all the required settings. Creating an assignment should look a bit like this.

![Creating an Assignment Example](https://github.com/maniac22/Project-3/blob/master/artifacts/img/example3.png)


## More information
  
  * The current stable version plugin only supports two competitive assignment modes,namely the Fastest and OptiMode.
  * The current stable plugin version supports multiple file submissions,but a specific naming convention for student submissions has to be followed as set in the plugin settings.
  * The current stable plugin version supports 3 languages namely C++11,Python3 and Java1.8.
  * When creating an optimode assignment,the testcase zip file which contains input for the student's submission should be named in such a way that there exist a "testcase" substring,and the grading function should contain "evaluator".

## Developers
We have left some Artifacts about the project in the [wiki](https://github.com/kat-lego/Project-3/wiki). It should be a usefull read for understanding the project.
