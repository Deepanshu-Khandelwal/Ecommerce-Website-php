$(function () {
  // Datatable
  if ($('#example1').length) {
    $('#example1').DataTable();
  }
  // CK Editor
  if ($('#editor1').length) {
    CKEDITOR.replace('editor1');
  }
});

$(function(){
  if ($('.zoom').length) {
    $('.zoom').magnify();
  }
});

$(function(){
  $('#navbar-search-input').focus(function(){
    $('#searchBtn').show();
  });

  $('#navbar-search-input').focusout(function(){
    // Small delay to allow clicking the search button before hiding
    setTimeout(function() {
      $('#searchBtn').hide();
    }, 200);
  });

  getCart();

  $(document).on('click', '.btn-close', function(){
    $('#callout').hide();
  });
  
  $(document).on('click', '.close', function(){
    $('#callout').hide();
  });
});

function showAlert(message, type) {
  if ($('#toast-container').length === 0) {
    $('body').append('<div id="toast-container"></div>');
  }

  var toastId = 'toast-' + Date.now();
  var iconClass = (type === 'success') ? 'fa-solid fa-circle-check toast-icon-success' : 'fa-solid fa-triangle-exclamation toast-icon-danger';
  var titleText = (type === 'success') ? 'Success' : 'Attention';

  var toastHtml = `
    <div class="premium-toast" id="${toastId}">
        <i class="${iconClass} toast-icon"></i>
        <div class="toast-content">
            <div class="toast-title">${titleText}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button type="button" class="toast-close" onclick="$('#${toastId}').addClass('hide'); setTimeout(function(){ $('#${toastId}').remove(); }, 400);"><i class="fa-solid fa-xmark"></i></button>
        <div class="toast-progress"></div>
    </div>
  `;

  $('#toast-container').append(toastHtml);

  setTimeout(function() {
    $('#' + toastId).addClass('hide');
    setTimeout(function() {
      $('#' + toastId).remove();
    }, 400);
  }, 4000);
}

function getCart(){
  $.ajax({
    type: 'POST',
    url: 'cart_fetch.php',
    dataType: 'json',
    success: function(response){
      $('#cart_menu').html(response.list);
      $('.cart_count').html(response.count);
    }
  });
}
