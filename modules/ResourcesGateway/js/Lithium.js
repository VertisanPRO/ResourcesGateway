if (payments_count > 0) {
  document.querySelector("div .pairs dl").insertAdjacentHTML('afterend', '<dl><dt>Purchases</dt><dd>'+payments_count+'</dd></dl>');
}
if (res_id > 0) {
  document.querySelector("main div .ms-auto").innerHTML += '<a href="'+gateway_url+'" class="btn btn-primary">'+res_gateway_button+'</a>';
}



