document.querySelector("div .pairs dl").insertAdjacentHTML('afterend', '<dl><dt>Purchases</dt><dd>'+payments_count+'</dd></dl>');
if (res_id > 0) {
  document.querySelector("main div .ms-auto").innerHTML += '<a href="'+stripe_form_url+'" class="btn btn-primary">'+res_purchase_for_price+' Card</a>';
}



