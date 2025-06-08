// JS for portal settings page
$(function(){
  loadSettings();
  $('#settingsForm').on('submit', function(e){
    e.preventDefault();
    const data = {
      shipping_key: $('#shipping_key').val(),
      woocommerce_ck: $('#woocommerce_ck').val(),
      woocommerce_cs: $('#woocommerce_cs').val(),
      store_url: $('#store_url').val(),
      language: $('#language').val(),
      time_zone: $('#time_zone').val(),
      currency: $('#currency').val()
    };
    $.ajax({
      url: `${BASE_URL}/assets/cPhp/update_settings.php`,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(data)
    }).done(()=>{
      alert('Settings saved');
    }).fail(xhr=>{
      alert('Save failed: ' + (xhr.responseJSON?.error || xhr.statusText));
    });
  });
});

function loadSettings(){
  $.getJSON(`${BASE_URL}/assets/cPhp/get_settings.php`, function(data){
    $('#shipping_key').val(data.shipping_key || '');
    $('#woocommerce_ck').val(data.woocommerce_ck || '');
    $('#woocommerce_cs').val(data.woocommerce_cs || '');
    $('#store_url').val(data.store_url || '');
    $('#language').val(data.language || '');
    $('#time_zone').val(data.time_zone || '');
    $('#currency').val(data.currency || '');
  });
}
