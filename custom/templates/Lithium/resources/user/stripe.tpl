{extends file="user/layout.tpl"}

{block name=sHeading}

  <div class="d-flex align-items-center mb-4">
    <h2>Stripe</h2>
  </div>

{/block}

{block name=sContent}

  {if isset($SUCCESS)}
    <div class="alert alert-success">
      {$SUCCESS}
    </div>
  {/if}

  {if isset($ERROR)}
    <div class="alert alert-danger">
      {$ERROR}
    </div>
  {/if}



<div class="card">
  <div class="card-header">
    Stripe
  </div>
  <div class="card-body">
    <form action="" method="post">
      <div class="form-group">
        <label for="publishable_key" class="form-label">PUBLISHABLE KEY</label>
        <input type="password" class="form-control" id="publishable_key" name="publishable_key" value="{$PUBLISHABLE_KEY}">
      </div>
      <div class="form-group">
        <label for="secret_key" class="form-label">SECRET KEY</label>
        <input type="password" class="form-control" id="secret_key" name="secret_key" value="{$SECRET_KEY}">
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">{$SUBMIT}</button>
      </div>
      <input type="hidden" name="token" value="{$TOKEN}">
    </form>
  </div>
</div>
{/block}