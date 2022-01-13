{extends file="layout.tpl"}

{block name=heading}

  <h2>{$RESOURCE->name}</h2>

{/block}

{block name=content}

  {if isset($ERROR)}
    <div class="alert alert-danger">
      {$ERROR}
    </div>
  {/if}

  {if isset($SUCCESS)}
    <div class="alert alert-success">
      {$SUCCESS}
    </div>
  {/if}

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  Stripe.setPublishableKey('{$PUBLISHABLE_KEY}');
  $(function() {
    var $form = $('#payment-form');
    $form.submit(function(event) {
    // Disable the submit button to prevent repeated clicks:
    $form.find('.submit').prop('disabled', true);
      
    // Request a token from Stripe:
    Stripe.card.createToken($form, stripeResponseHandler);
      
    // Prevent the form from being submitted:
    return false;
    });
  });
  function stripeResponseHandler(status, response) {
    // Grab the form:
    var $form = $('#payment-form');
      
    if (response.error) { // Problem!
      // Show the errors on the form:
      $form.find('.payment-errors').text(response.error.message);
      $form.find('.submit').prop('disabled', false); // Re-enable submission
    }else { // Token was created!
      // Get the token ID:
      var token = response.id;
    // Insert the token ID into the form so it gets submitted to the server:
      $form.append($('<input type="hidden" name="stripeToken">').val(token));
    // Submit the form:
      $form.get(0).submit();
    }
  };
</script>
<div class="card">
  <div class="card-body">
    <form action="{$PROCESS_URL}" method="post" name="cardpayment" id="payment-form">
      <input type="hidden" name="token" value="{$TOKEN}">
			<input type="hidden" name="res_id" value="{$RESOURCE->id}">
      <input type="hidden" name="amount" value="{$RESOURCE->price}">
      <div class="card-header">
        {$RESOURCE->name}  {$RESOURCE->price} {$CURRENCY}
      </div>
 
      <div class="form-group">
        <label class="form-label" for="name">{$CARD_HOLDER_NAME}</label>
        <input name="holdername" id="name" class="form-input form-control" type="text" value="" required />
      </div>               
      <div class="form-group">
        <label class="form-label" for="email">{$EMAIL}</label>
        <input name="email" id="email" class="form-input form-control" type="email" value="{$USER->email}" required />
      </div>         
      <div class="form-group">
        <label class="form-label" for="card">{$CARD_NUMBER}</label>
        <input name="cardnumber" id="card" class="form-input form-control" type="text" maxlength="16" data-stripe="number" value="" required />
      </div>
      <div class="row">
          <div class="col-lg-4">
              <label class="form-label">{$MONTH}</label>
              <select name="month" id="month" class="form-input2 form-control" data-stripe="exp_month">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
            </div>
            <div class="col-lg-4">
              <label class="form-label">{$YEAR}</label>
              <select name="year" id="year" class="form-input2 form-control" data-stripe="exp_year">
                <option value="21">2021</option>
                <option value="22">2022</option>
                <option value="23">2023</option>
                <option value="24">2024</option>
                <option value="25">2025</option>
                <option value="26">2026</option>
                <option value="27">2027</option>
                <option value="28">2028</option>
                <option value="29">2029</option>
                <option value="30">2030</option>
              </select>
            </div>
            <div class="col-lg-4">
              <label class="form-label">{$CVV}</label>
              <input name="cvv" id="cvv" class="form-input2 form-control" type="text" placeholder="CVV" data-stripe="cvc" value="" required />
            </div>
      </div>
      <div class="form-group">
        <div class="payment-errors"></div>
      </div>
      <div class="form-group">
        <div class="button-style">
          <button class="btn btn btn-primary submit" style="width: 100%;">{$PAY_NOW}</button>
        </div>
      </div>
    </form>
  </div>
</div>
{/block}



