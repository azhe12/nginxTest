<?php
// this application can connect via REST (XML over HTTP) or SOAP
// define one version of METHOD to choose.
// define('METHOD', 'SOAP');
define('METHOD', 'REST');

// make sure to create a cache directory an make it writable
define('CACHE', 'cache'); // path to cached files
define('ASSOCIATEID', 'webservices-20'); //put your associate id here
define('DEVTAG', 'DHBIBVFDMBJDL'); // put your developer tag here

//give an error if software is run with the dummy devtag
if(DEVTAG=='XXXXXXXXXXXXXX')
{
  die ('You need to sign up for an Amazon.com developer tag at<a href =
     "https://associates.amazon.com/exec/panama/associates/join/developer/application.html/ref=sc_bb_l_0/002">Amazon</a>
       when you install this software.  You should probably sign up 
       for an associate ID at the same time. Edit the file constants.php.');
}

// (partial) list of Amazon browseNodes.
$categoryList = array(5=>'Computers & Internet', 3510=>'Web Development', 
                      295223=>'PHP', 17=>'Literature and Fiction', 
                      3=>'Business & Investing', 53=>'Non Fiction',
                      23=>'Romance', 75=>'Science', 21=>'Reference',
                      6 =>'Food & Wine', 27=>'Travel', 
                      16272=>'Science Fiction'
                     );
?>
