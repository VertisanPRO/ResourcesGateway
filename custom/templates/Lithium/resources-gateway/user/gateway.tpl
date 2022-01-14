{extends file="user/layout.tpl"}

{block name=sHeading}

  <div class="d-flex align-items-center mb-4">
    <h2>Resources Gateway</h2>
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

  <div class="col-xl-9 col-lg-8">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a href="#tab-stripe" class="nav-link active" data-bs-toggle="tab">Stripe</a>
      </li>
      <li class="nav-item">
        <a href="#tab-cent-app" class="nav-link" data-bs-toggle="tab">Cent-App</a>
      </li>
      <li class="nav-item">
      <a href="#tab-settings" class="nav-link" data-bs-toggle="tab">Settings</a>
    </li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade active show" id="tab-stripe">
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
      </div>

      <div class="tab-pane fade" id="tab-cent-app">
        <div class="card">
          <div class="card-header">
            CENT-APP
          </div>
          <div class="card-body">
            <form action="" method="post">
              <div class="form-group">
                <label for="centapp_key" class="form-label">API KEY</label>
                <input type="password" class="form-control" id="centapp_key" name="centapp_key" value="{$CENTAPP_KEY}">
              </div>
              <div class="form-group">
                <label for="centapp_shop" class="form-label">SHOP ID</label>
                <input type="password" class="form-control" id="centapp_shop" name="centapp_shop" value="{$CENTAPP_SHOP}">
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary">{$SUBMIT}</button>
              </div>
              <input type="hidden" name="token" value="{$TOKEN}">
            </form>
          </div>
            <div class="card-body">
              <strong>API LINKS:</strong>
              <p>SUCCESS URL: <strong>{$CENT_SUCCESS_URL}</strong></p>
              <p>FAIL URL: <strong>{$CENT_FAIL_URL}</strong></p>
              <p>RESULT URL: <strong>{$CENT_LISTENER_URL}</strong></p>
            </div>
        </div>
      </div>

      <div class="tab-pane fade" id="tab-settings">
        <div class="card">
          <div class="card-header">
            Gateway Settings
          </div>
          <div class="card-body">
            <form action="" method="post">
              <div class="form-group">
                <label for="gateway_status" class="form-label">Gateway Status</label>
                <select class="form-control" name="gateway_status">
                  <option value="enable" {if $GATEWAY_STATUS == 'enable'} selected{/if}>Enable</option>
                  <option value="disable" {if $GATEWAY_STATUS != 'enable'} selected{/if}>Disable</option>
                </select>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn btn-primary">{$SUBMIT}</button>
              </div>
              <input type="hidden" name="token" value="{$TOKEN}">
            </form>
          </div>
        </div>
      </div>
    </div>


    
  </div>
{/block}