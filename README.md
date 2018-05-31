# Princeton-Plainsboro

<p align="center">
  <img src="http://victor-fauquembergue.fr/imagesGit/princeton0">
</p>

We had to implement a web application in PHP-HTML-CSS-MySQL. This application is general practitioner appointment management system. It can be used for a clinic or a hospital to allow patients and doctors to create, manage or delete appointments. This web application can be managed by an admin who can manage doctors and also the web-site's contains.

Feel free to contribute or discuss this application. Please contact me if you find a bug. 

## Contents
  * [Install][Installation]
    * [Web-site][Website]
    * [Database][Database]
    * [Launch][Launch]
  * [Gameplay][Gameplay]
    * [The city][City]
    * [The divinity][Divinity]
    * [The units][Unit]
    * [The wonders][Wonder]
    * [The scientists][Scientists]
    * [Screenshots][Screenshots]
  * [Contribute][Contribute]
    * [Execute the tests][Tests]
    * [Check the code style][CheckXo]
  * [Team][Team]

## Install

### Web-site

* Go in your repository : 
  * Mac and MAMP : ```bash cd ./Applications/MAMP/htdocs/```
  * Windows and WAMP : ```bash cd ./wamp/www/```
  * Linux and LAMP : I'm sorry, I don't know 
* Clone ```bash git clone https://github.com/UKyz/Princeton-Plainsboro.git```

### Database

* Go in phpMyAdmin :
  * Launch your browser :
    * Mac and MAMP : ```localhost:8888/phpMyAdmin/``` (User: root, Password: root)
    * Windows and WAMP : ```http://localhost/phpmyadmin/``` (User: root, Password: [nothing])
    * Linux and LAMP : I'm sorry, I don't know
  * New Database
  * Import
  * Choose the file
    * Pick ```g_practitioner.sql```
  * Execute
* For Windows only : 
  * Open code files in a text editor :
    * Change every ```new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', 'root')``` in ```new PDO('mysql:host=localhost;dbname=g_practitioner;charset=utf8', 'root', '')```
    
### Launch 

* Launch your browser :
  * Mac and MAMP : ```localhost:8888/Princeton-Plainsboro/```
  * Windows and WAMP : ```http://localhost/Princeton-Plainsboro```
  * Linux and LAMP : I'm sorry, I don't know

## Requierements

* A browser
* MAMP for MacOS [(install link)][MAMP] or WAMP for Windows [(install link)][WAMP] or LAMP for Linux [(install link)][LAMP]
* A text Editor

## Gameplay
 The goal of the game is to win over the rival city. You will have, at your service, a city full of resources, units, scientists, wonders and also a divinity to believe in. As a master of the city, you will be able to do a lot of actions. First of all, you will need some units to defend the land, because the enemy can come over at every moment and attack your city. If they win, they will steal a lot of resources. Despite that you can also attack the rival whenever you want. <br />
 Then you will need resources to operate, you will be able to buy, chop and sell resources. You will manage your stocks of gold, corn and wood. Gold will be usefull for everything, you will need corn to heal your units most of the time, and finally wood will be precious to build wonders and help your city to grow faster. <br />
 Every city has a divinity, so that you will be having one too. You will need to be kind with your pray, because the divinity is a little bit capricious. By offering resources to the divinity, you will be able to receive some favors or blessings. This can be helpfull in the tough moments. <br />
 And finally your city will have some clever guys. The scientists need some gold to be smarter, but the scientists can be the key of winning. Indeed you can win by science if your clever guys are high evolved.
 
  ### The city
  You will control a city, your city. You need to bring the city at the top to win the battle.
  ### The divinity
  You will have a divinity in your city, you will be able to offer resources to the divinity. Wait and see if the divinity blesses you in return. But be careful, the divinity is capricious.
  ### The units
  You will have, to defend your city, some units. There can be some hapinness birth or some tragedic death in the ranks of units. You will be able to form units and prepare them to fight for you.
  ### The wonders
  You will be able to build wonders to help you win the battle. Wonders are beautiful and also very helpful to grow faster the number of resources of your city.
  ### The scientists
  You will have some scientists in your ranks, try to offer them some gold to see if they can help you and your city to be gretter. 
  
  ### Screenshots
  
 <p align="center">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot1">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot2">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot3">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot4">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot5">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot6">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot7">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/screenshot8">
 </p>
  

## Contribute 
### Execute the tests
  This project is based on the BDD method. Every classes has a test file to test what's required to be executed. The test can also show a bogue when the class is modified. [(See what BDD is)][BDDWiki]
  
  To test every classes : 
  
```bash
mocha
```

### Check the code style
  This project uses the framework xojs. xojs is a ESLint wrapper that enforce strict and readable code. Everything works fine with xo [(See what xo is)][xo], if you want to check xo you just need to execute : 
  
```bash
xo
```

## Credits
  * [Couton Alexia][Alexia] : (Scientists)
  * [Fauquembergue Victor][Me] (Main, Update divinity (classe given with the project), Wonder, Unit, City) 
  * Lagadec Julien (Some ideas)

[MAMP]: https://www.mamp.info/en/downloads/
[WAMP]: http://www.wampserver.com/#download-wrapper
[LAMP]: https://doc.ubuntu-fr.org/lamp
[BDDWiki]: https://en.wikipedia.org/wiki/Behavior-driven_development
[xo]: https://github.com/xojs/xo
[Install]: https://github.com/UKyz/Princeton-Plainboro/blob/master/README.md#install
[Website]: https://github.com/UKyz/Princeton-Plainboro/blob/master/README.md#web-site
[Database]: https://github.com/UKyz/Princeton-Plainboro/blob/master/README.md#database
[Launch]: https://github.com/UKyz/Princeton-Plainboro/blob/master/README.md#launch
[Gameplay]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#gameplay
[City]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#the-city
[Unit]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#the-units
[Scientists]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#the-scientists
[Wonder]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#the-wonders
[Divinity]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#the-divinity
[Screenshots]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#screenshots
[Contribute]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#contribute
[Tests]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#execute-the-tests
[CheckXo]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#check-the-code-style
[Team]: https://github.com/UKyz/TPSevenWondersJS/blob/master/README.md#team-
[Alexia]: https://github.com/Alexia14
[Me]: https://github.com/UKyz
