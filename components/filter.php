<?php
    $inputId = $inputId ?? 'searchInput';
    $placeholder = $placeholder ?? 'Search by User ID or Name...';
?>

<!-- this is the search component -->
<div class="col-md-4">
  <div class="input-group input-group-sm">
    <span class="input-group-text"><i class="fas fa-search"></i></span>
    <input type="text" id="<?= htmlspecialchars($inputId) ?>" class="form-control" 
           placeholder="<?= htmlspecialchars($placeholder) ?>" 
           onkeyup="filterTable('<?= htmlspecialchars($inputId) ?>', 'short')">
  </div>
</div>

<script src="../script/response.js"></script>