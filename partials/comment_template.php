<div class="comment" id="comment-<?php echo $comment->id; ?>">
    <div class="comment-header">
        <p class="comment-author">
            <strong class="comment-username"><?php echo htmlspecialchars($comment->username); ?></strong> 
            <span class="comment-timestamp">
                (ID: <?php echo $comment->id; ?>) -
                <?php echo date('j. n. Y H:i', strtotime($comment->created_at)); ?>
            </span>
        </p>
    </div>
    <div class="comment-body">
        <p class="comment-content"><?php echo htmlspecialchars($comment->content); ?></p>
    </div>

    <?php if (isset($_COOKIE['loggedIn']) ): ?>
        <div class="comment-actions">
        <button class="edit-comment" data-comment-id="<?php echo $comment->id; ?>" onclick="editComment(<?php echo $comment->id; ?>)">Edit</button>
            <button class="delete-comment" data-comment-id="<?php echo $comment->id; ?>" onclick="deleteComment(<?php echo $comment->id; ?>)">Delete</button>
        </div>
    <?php endif; ?>
</div>





<style>.comment-timestamp, 
.comment-content { 
    font-size: 0.8em; 
    color: #777;      
    margin-left: 10px; 
}
</style>

<script src="main.js"></script>