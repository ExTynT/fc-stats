
<?php if (isset($_COOKIE['loggedIn'])): ?>
<div class="comment-form-container news-item">  <form id="commentForm" method="POST" action="/partials/add_comment.php">
        <input type="hidden" name="news_id" value="<?php echo $newsItem->id; ?>">
        <textarea name="content" class="comment-textarea" placeholder="Add your comment..." required></textarea>
        <button type="submit" class="comment-submit-button">Submit Comment</button>
    </form>
</div>
<?php else: ?>
<p>Please <a href="/partials/signup_page.php">log in or sign up</a> to comment.</p>
<?php endif; ?>

<style>
.comment-form-container {
    
    
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}


.comment-textarea {
    width: calc(100% - 30px); 
    height: 100px; 
    padding: 10px;
    border: 1px solid #ccc;
    resize: vertical; 
}


.comment-submit-button {
    background-color: #007bff; 
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
}

.comment-submit-button:hover {
    background-color: #0056b3; 
}</style>