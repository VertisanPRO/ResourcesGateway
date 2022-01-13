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

<div class="card">
  <div class="card-body">
    {if $STRIPE}
      <a class="btn btn-primary" href="{$STRIPE_FORM_URL}">{$STRIPE_LABEL}</a>
    {/if}

    {if $CENT_APP}
      <a class="btn btn-primary" href="{$CENT_APP_PROCESS_URL}">{$CENT_APP_LABEL}</a>
    {/if}
  </div>
</div>
{/block}



