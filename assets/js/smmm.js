/**********************************************************************
                      Flash messages
 **********************************************************************/
document.addEventListener("DOMContentLoaded", whenDocumentReady);

function whenDocumentReady () {
  // Handler for form validation errors occuring through AJAX
  $(window).on('ajaxErrorMessage', function(event, message){
    smmmDisplayMessage(message, 'danger');

    event.preventDefault();
  });

  // Defined the date pickers
  var datepickerElements = document.getElementsByClassName('datepicker');
  for (var i = 0; i < datepickerElements.length; ++i) {
      datepickerElements.datepicker();
  }
}



//Displays the flash messages
function smmmDisplayMessage (message, type) {
  var allAlertClasses = '';
  var allAlertTypes = ['success', 'info', 'warning', 'danger'];

  allAlertTypes.forEach(function(element, index, array) {
    allAlertClasses = allAlertClasses + ' alert-' + element;
    }
  );

  if (allAlertTypes.indexOf(type) != -1) {
    smmmMessageDiv = document.getElementById('smmmMessage');
    smmmMessageDiv.parentNode.className = smmmMessageDiv.parentNode.className.replace(/\balert-[a-z]{1,}/g, '');
    smmmMessageDiv.parentNode.className = smmmMessageDiv.parentNode.className.replace(/\bhidden/g, '');
    smmmMessageDiv.parentNode.className += ' alert-' + type;
    smmmMessageDiv.innerHTML = message;

    setTimeout ( function () {
      smmmHideMessage(smmmMessageDiv);
    }, 10000);
  }
  else {
    alert('error occurred in smmmDisplayMessage. type: ' + type + ' - message: ' + message);
  }
}


//Hides flash messages
function smmmHideMessage (element) {
  element.parentNode.className += ' hidden';
}

