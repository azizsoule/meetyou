function getMessages(id_discussion) {

  var path = 'messaging/php/getmessages.php?dis=';

  $.post(path.concat(id_discussion), function(data) {
      $('.messages').html(data);
  });
}

setInterval("getMessages(document.getElementById('id_dis').value)", 100);
