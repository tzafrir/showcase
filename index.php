<!doctype html>
<head>
<title>Showcase+</title>
<style>
  .container {
    margin: 10px;
    padding: 3px;
    background-color: #ccc;
    display: block;
    box-shadow: 2px 2px 2px #334;
    width: auto;
    height: auto;
  }

  .container:hover {
    background-color: #eee;
  }

  #sc0 {
    display: block;
    float: left;
    max-width: 45%;
  }

  #sc1 {
    display: block;
    float: right;
    max-width: 45%;
  }

  #sc2 {
    display: block;
    float: left;
    clear: left;
    max-width: 45%;
  }

  #bigphoto {
    box-shadow: 0 0 64px #005;
    border: 1px solid black;
    display: block;
  }

  #photoPosts {
    display: block;
    float: right;
  }

  #photoPosts img {
    float: right;
    text-align: justify;
  }

  a {
    text-decoration: none;
    color: inherit;
  }

  .fatPost {
    font-family: 'Convergence', sans-serif;
    font-size: 1.2em;
  }
</style>
<script src="https://www.google.com/jsapi?key=ABQIAAAAq9aptPa0BR2-BRTarx5CrBSKcS1aLn7EQ-cqq9PuGyFd-haD5hSwP6gKxkGJMELTP4D2BcurIdt15g" type="text/javascript"></script>
<script>
  google.load("jquery", "1.7.0");
  google.load("jqueryui", "1.8.16");

  userId = "115470071077898720170";
  key = "AIzaSyDWGHSeLL0NMLN-K5XvtEU58Vg56GanCT4";

  function onLoad() {
    jQuery.getJSON('https://www.googleapis.com/plus/v1/people/' + userId + '/activities/public?' +
        'key=' + key +
        '&maxResults=100' +
        '&callback=?',
        getData);
    jQuery.getJSON('https://www.googleapis.com/plus/v1/people/' + userId + '?' +
        'key=' + key +
        '&callback=?',
        getProfile);
  }

  function setUp() {
    $('#sc1').append('<div class="container" id="photoPosts"></div>');
    $('#sc2').append('<div id="fatPosts"></div>');
  }

  function getData(data) {
    var sc = $('#sc0');
    sc.text('');
    sc.append('<ul>');
    setUp();
    for (var i = 0; i < data.items.length; ++i) {
      if (data.items[i].verb != "post") {
        continue;
      }

      var obj = data.items[i].object;
      if (isAside(obj)) {
        addAside(obj);
      } else if (hasPhoto(obj)) {
        addPhotoPost(obj);
        addFatPost(obj);
      } else {
        addFatPost(obj);
      }
    }
  }

  function isEmpty(object) {
    return object.content == '';
  }

  function addAside(aside) {
    var sc = $('#sc0');
    sc.append('<div class="aside container">' + '<a target="_blank" href="' + aside.url + '">' +
      aside.content + '</a></div>');
  }

  function addFatPost(object) {
    if (object.content == '') {
      return;
    }
    var sc = $('#fatPosts');
    sc.append('<div class="fatPost container">' + '<a target="_blank" href="' + object.url + '">' +
      object.content + '</a></div>');
  }

  function getProfile(profile) {
    var header = $("#head1");
    header.text(profile.displayName);
    addProfilePhoto(profile.image.url);
  }

  function isAside(object) {
    return !(isEmpty(object) || object.objectType != "note" || object.content.length > 140) &&
        !hasPhoto(object);
  }

  function hasPhoto(object) {
    if (!object.attachments) {
      return false;
    }
    for (var i = 0; i < object.attachments.length; ++i) {
      if (object.attachments[i].objectType == "photo") {
        return true;
      }
    }
    return false;
  }

  function addPhotoPost(object) {
    var rc = $('#photoPosts');
    rc.append('<a target="_blank" href="' + object.url + '">' +
      '<img src="' + getPhotoLink(object) + '" /\>' +
    '</a>');
  }

  function getPhotoLink(object) {
    if (!object.attachments) {
      return '';
    }
    for (var i = 0; i < object.attachments.length; ++i) {
      var att = object.attachments[i];
      if (att.objectType == "photo") {
        return att.image.url;
      }
    }  
  }

  function addProfilePhoto(photoUrl) {
    $("#photoContainer").html('<img id="bigphoto" src="' + photoUrl + '&sz=256" /\>');
  }

  google.setOnLoadCallback(onLoad);
</script>
<link href='http://fonts.googleapis.com/css?family=Convergence' rel='stylesheet' type='text/css'>
</head><body>
<div id="header">
  <h1 id="head1">Showcase+</h1>
  <div align="center" id="photoContainer"></div>
</div>
<div id="wrap">
  <div id="sc0">Loading...</div>
  <div id="sc1"></div>
  <div id="sc2"></div>
</div>
