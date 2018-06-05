# Princeton-Plainsboro

<p align="center">
  <img src="http://victor-fauquembergue.fr/imagesGit/princeton0">
</p>

We have implemented a web application in PHP-HTML-CSS-MySQL. This application is a general practitioner appointment management system. It can be used for a clinic or a hospital to allow patients and doctors to create, control or delete appointments. This web application can be managed by an admin who can manage doctors and also the web-site's contains.

Feel free to contribute or discuss this application. Please contact me if you find a bug. 

## Contents
  * [Install & Launch][Installation]
    * [Website][Website]
    * [Database][Database]
    * [Launch][Launch]
  * [Requierements][Requierements]
  * [Details][Details]
    * [Home][Home]
    * [Services][Services]
    * [Consultation Fees][Consultation]
    * [Resources][Resources]
    * [Appointements][Appointements]
    * [Contact][Contact]
    * [Screenshots][Screenshots]
  * [Credits][Credits]

## Install & Launch

### Website

* Go in your repository : 
  * Mac and MAMP : ```cd ./Applications/MAMP/htdocs/```
  * Windows and WAMP : ```cd ./wamp/www/```
  * Linux and LAMP : I'm sorry, I don't know 
* Clone ```git clone https://github.com/UKyz/Princeton-Plainsboro.git```

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

## Details
  The goal of this application is to make life easier for doctors and patients. This service is using a databse which is managing every doctors and every patients in the hospital, but also any appointments they can create or ask by himself. And finally all pages' contents are created and managed by the admin thanks to the databse.
  Every patients and every doctors need to sign up to use this application. With your account you can create, control or delete appointements by a simple click. 
  This application can be managed by an admin. This admin is allow to change the pages' contains. He can add texts and photos in every pages. He can also managed patients' and doctors' accounts, every doctor has to be confirmed by the admin to be a part of the doctors' list.
  
  ### Public
  Home :
  In the home page you can see the opening hours of the day and also some photos of the clinic.

  Services :

  You can see the doctors with photos, and there is also a link if you are a doctor and you want to join de clinic.

  Consultation Fees :

  In this page there is many questions that are uselly asked about consultations and appointements.

  Resources :

  There is many usefull links about healthcare.

  Appointments :

  You can create and manage your appointments in this page. But first of all you need to sign up or log in.

  Contact :

  You can see all contact that patients need and also the opening hours.
  
  ### Admin
  
  The admin can create, manage or delete pages' contain. He's able to put texts, photos, links in all public's pages.
  He is also the one who can accept new doctor. 
  
  ### Doctor
  
  The doctor can accept or refuse an appointements asked by a patient. He can also add a comment. 
  
  ### Patient
  
  The patient can take or cancel an appointement. He's also able to see old appointements and next one.
  
  ### Screenshots
  
 <p align="center">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton1">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton2">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton3">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton4">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton5">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton6">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton7">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton8">
  <br />
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton9">
  <img width="356" height="200" src="http://victor-fauquembergue.fr/imagesGit/princeton10">
 </p>

## Credits
  * [Couton Alexia][Alexia] : (Appointements)
  * [Fauquembergue Victor][Me] (Doctors, Admin, Patients, CSS)

[MAMP]: https://www.mamp.info/en/downloads/
[WAMP]: http://www.wampserver.com/#download-wrapper
[LAMP]: https://doc.ubuntu-fr.org/lamp
[Installation]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#install--launch
[Website]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#web-site
[Database]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#database
[Launch]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#launch
[Requierements]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#requierements
[Details]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#details
[Home]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#home
[Services]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#services
[Consultation]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#consultation-fees
[Resources]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#resources
[Appointements]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#appointements
[Contact]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#contact
[Screenshots]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#screenshots
[Credits]: https://github.com/UKyz/Princeton-Plainsboro/blob/master/README.md#credits
[Alexia]: https://github.com/Alexia14
[Me]: https://github.com/UKyz
