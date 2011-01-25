Zend Personal Lib
=================

My personal Zend Framework class library. Very basic stuff currently but just learning.

## Contents

Currently just contains

* **Image**
* * **Thumb** - Thumbnail creator
* **Optimize**
* * **Concat** - Concatention system, helper needs finished.
* **Validate**
* * **Facebook** - Does a simple regex validation and 404 validation on facebook URLs.
* * **Twitter** -  Does a simple regex validation and 404 validation on twitter URLs.
* * **Tvrage** - Does a simple regex validation and 404 validation on tvrage URLs.
* * **Imdb** - Does a simple regex validation and 404 validation on IMDb URLs.
* * **Page** - The class that does the 404 validation, Facebook,Twitter,Tvrage,Imdb all inherit from this. 
* **Service**
* * **Amazon**
* * * **S3** - Simple wrapper to avoid redoing the upload code

## TODO

* **Iain_Validate_Twitter** - Add validation that account exists.
* **Iain_Validate_Facebook** - Add validation that account exists.
* **Iain_Optimize_Concat** - Add veiw helper
* **ALL** - Improve!