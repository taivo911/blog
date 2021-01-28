<?php require_once APPROOT.'/views/inc/header.php'; ?>
<h1>Posts</h1>
<?php foreach ($data['posts'] as $post): ?>
    <div class="card card-body mb-3">
        <h3 class="card-title"><?php echo $post->title; ?></h3>
        <div class="bg-light p-2 mb-3">Created by <?php echo $post->name; ?> at <?php echo $post->postCreated; ?></div>
        <p class="card-text"><?php echo $post->content; ?></p>
    </div>
<?php endforeach; ?>
<?php require_once APPROOT.'/views/inc/footer.php'; ?>
