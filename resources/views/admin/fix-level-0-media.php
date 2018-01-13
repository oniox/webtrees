<?php use Fisharebest\Webtrees\Bootstrap4; ?>
<?php use Fisharebest\Webtrees\I18N; ?>
<?php use Fisharebest\Webtrees\View; ?>

<?= Bootstrap4::breadcrumbs([route('admin-control-panel') => I18N::translate('Control panel')], $title) ?>

<h1><?= $title ?></h1>

<p>
	<?= I18N::translate('If a media object is linked to an individual, when it should be linked to a fact or event, then you can move it to the correct location.') ?>
</p>

<table class="table table-bordered table-sm table-hover table-responsive datatables wt-fix-table" data-ajax="<?= e(json_encode(['url' => route('admin-fix-level-0-media-data')])) ?>" data-server-side="true" data-state-save="true">
	<caption class="sr-only">
		<?= I18N::translate('Media objects') ?>
	</caption>
	<thead class="thead-dark">
		<tr>
			<th data-sortable="false"><?= I18N::translate('Family tree') ?></th>
			<th data-sortable="false"><?= I18N::translate('Media object') ?></th>
			<th data-sortable="false"><?= I18N::translate('Title') ?></th>
			<th data-sortable="false"><?= I18N::translate('Individual') ?></th>
			<th data-sortable="false"><?= I18N::translate('Facts and events') ?></th>
		</tr>
	</thead>
</table>

<?php View::push('javascript') ?>
<script>
  'use strict';

  // If we click on a button, post the request and reload the table
  document.querySelector(".wt-fix-table").onclick = function (event) {
    let element = event.target;
    if (element.classList.contains("wt-fix-button")) {
      event.stopPropagation();
      if (confirm(element.dataset.confirm)) {
        $.ajax({
          data: {
            "fact_id":   element.dataset.factId,
            "indi_xref": element.dataset.individualXref,
            "obje_xref": element.dataset.mediaXref,
            "tree_id":   element.dataset.treeId
          },
          method: "POST",
          url: <?= json_encode(route('admin-fix-level-0-media-action')) ?>
        }).done(function () {
          $(".wt-fix-table").DataTable().ajax.reload(null, false);
        });
      }
    }
  };
</script>
<?php View::endpush() ?>