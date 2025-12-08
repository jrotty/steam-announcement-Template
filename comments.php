<?php
/**
 * 评论模板
 * 
 * @package SteamAnnouncement
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<div id="comments" class="comments-area">
    <h3 class="comments-title">
        <?php $this->commentsNum('暂无评论', '1条评论', '%d条评论'); ?>
    </h3>
    
    <?php if ($this->allow('comment')): ?>
    <div id="respond" class="comment-respond">
        <h3 id="reply-title" class="comment-reply-title">
            <?php _e('发表评论'); ?>
            <?php if ($this->user->hasLogin()): ?>
            <span class="logged-in-as">
                <?php _e('登录身份: '); ?>
                <a href="<?php $this->options->profileUrl(); ?>" class="username"><?php $this->user->screenName(); ?></a>.
                <a href="<?php $this->options->logoutUrl(); ?>" title="<?php _e('退出登录'); ?>" class="logout-link"><?php _e('退出'); ?></a>
            </span>
            <?php endif; ?>
        </h3>
        
        <form action="<?php $this->commentUrl() ?>" method="post" id="commentform" class="comment-form">
            <?php if (!$this->user->hasLogin()): ?>
            <div class="comment-form-fields">
                <div class="comment-form-author">
                    <label for="author"><?php _e('昵称'); ?> <span class="required">*</span></label>
                    <input type="text" name="author" id="author" class="text" size="30" value="<?php $this->remember('author'); ?>">
                </div>
                <div class="comment-form-email">
                    <label for="email"><?php _e('邮箱'); ?> <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="text" size="30" value="<?php $this->remember('email'); ?>">
                </div>
                <div class="comment-form-url">
                    <label for="url"><?php _e('网站'); ?></label>
                    <input type="url" name="url" id="url" class="text" size="30" value="<?php $this->remember('url'); ?>">
                </div>
            </div>
            <?php endif; ?>
            
            <div class="comment-form-comment">
                <label for="textarea"><?php _e('评论内容'); ?> <span class="required">*</span></label>
                <textarea rows="8" cols="50" name="text" id="textarea" class="textarea"><?php $this->remember('text'); ?></textarea>
            </div>
            
            <div class="form-submit">
                <input type="submit" name="submit" id="submit" class="submit btn btn-primary" value="<?php _e('提交评论'); ?>">
                <?php $this->commenter->hash(); ?>
                <input type="hidden" name="remember" value="1">
            </div>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if ($this->comments->have()): ?>
    <ol class="comment-list">
        <?php $this->comments->listComments(); ?>
    </ol>
    
    <?php $this->comments->pageNav('上一页', '下一页', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'comment-navigation', 'itemTag' => 'span', 'textTag' => 'a', 'currentClass' => 'current')); ?>
    
    <?php endif; ?>
</div>
