/**********************************************************************
                      Flash messages
 **********************************************************************/
// Handler for form validation errors occuring through AJAX
$(window).on('ajaxErrorMessage', function(event, message){
  smmmDisplayMessage(message, 'danger');

  event.preventDefault();
});

//Displays the flash messages
function smmmDisplayMessage (message, type) {
  var allAlertClasses = '';
  var allAlertTypes = ['success', 'info', 'warning', 'danger'];

  allAlertTypes.forEach(function(element, index, array) {
    allAlertClasses = allAlertClasses + ' alert-' + element;
    }
  );

  if (allAlertTypes.indexOf(type) != -1) {
  //  $('#flashMessageContent').parent().addClass('hidden');
    $('#smmmMessage').parent().removeClass(allAlertClasses);
    $('#smmmMessage').parent().removeClass('hidden');
    $('#smmmMessage').parent().addClass('alert-' + type);
    $('#smmmMessage').html(message);
  }
  else {
    alert('error occurred in smmmDisplayMessage. type: ' + type + ' - message: ' + message);
  }

}

//Hides flash messages
function smmmHideMessage (element) {
  $(element).parent().addClass('hidden');
}

if ($('.datepicker').length) {
  $('.datepicker').datepicker();
}

if (window.location.hash.length && $('.tab-pane').length) {
  $('.tab-pane.active').removeClass('active');
  $('.nav-tabs .active').removeClass('active');
  $(window.location.hash).addClass('active');
  $(window.location.hash).show();
}