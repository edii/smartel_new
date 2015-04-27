<?php if($validate and is_array($_space) and count($_space) > 0): ?>
<!-- Sidebar .disk-space-widget -->
 <div class="sidebar-widget">
    <h5 class="title">Disk Space Usage</h5>
    <div class="content">
        <span class="icon16  icomoon-icon-storage-2 left"></span>
        <div class="progress progress-mini progress-success left tip" title="<?= $_space['parcent'] ?>%">
          <div class="bar" style="width: <?= $_space['parcent'] ?>%;"></div>
        </div>
        <span class="percent"><?= $_space['parcent'] ?>%</span>
        <div class="stat"><?= $_space['total'] ?> / <?= $_space['free'] ?></div>
    </div>

</div>
 <!-- End .disk-space-widget -->
 <?php endif; ?>
