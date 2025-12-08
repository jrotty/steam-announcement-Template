<?php
/**
 * 侧边栏模板
 * 
 * @package SteamAnnouncement
 */
?>

<aside class="sidebar">
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)): ?>
    <section class="sidebar-widget">
        <h3 class="widget-title">最新文章</h3>
        <ul class="widget-list">
            <?php $this->widget('Widget_Contents_Post_Recent')->to($recent); ?>
            <?php while($recent->next()): ?>
            <li class="widget-list-item">
                <a href="<?php $recent->permalink(); ?>" title="<?php $recent->title(); ?>"><?php $recent->title(); ?></a>
            </li>
            <?php endwhile; ?>
        </ul>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentComments', $this->options->sidebarBlock)): ?>
    <section class="sidebar-widget">
        <h3 class="widget-title">最新评论</h3>
        <ul class="widget-list">
            <?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
            <?php while($comments->next()): ?>
            <li class="widget-list-item">
                <a href="<?php $comments->permalink(); ?>" title="<?php $comments->author(); ?>: <?php $comments->excerpt(35, '...'); ?>">
                    <?php $comments->author(); ?>: <?php $comments->excerpt(35, '...'); ?>
                </a>
            </li>
            <?php endwhile; ?>
        </ul>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowCategory', $this->options->sidebarBlock)): ?>
    <section class="sidebar-widget">
        <h3 class="widget-title">文章分类</h3>
        <ul class="widget-list">
            <?php $this->widget('Widget_Metas_Category_List')->listCategories('wrapClass=widget-list&showCount=1&childCount=1'); ?>
        </ul>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowArchive', $this->options->sidebarBlock)): ?>
    <section class="sidebar-widget">
        <h3 class="widget-title">文章归档</h3>
        <ul class="widget-list">
            <?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=F Y')->to($archive); ?>
            <?php while($archive->next()): ?>
            <li class="widget-list-item">
                <a href="<?php $archive->permalink(); ?>" title="<?php $archive->date('F Y'); ?>">
                    <?php $archive->date('F Y'); ?> (<?php $archive->count(); ?>)
                </a>
            </li>
            <?php endwhile; ?>
        </ul>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowTagCloud', $this->options->sidebarBlock)): ?>
    <section class="sidebar-widget">
        <h3 class="widget-title">标签云</h3>
        <div class="widget-tagcloud">
            <?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true))->to($tags); ?>
            <?php while($tags->next()): ?>
            <a href="<?php $tags->permalink(); ?>" title="<?php $tags->name(); ?>" style="font-size: <?php echo $tags->weight + 100; ?>%;"><?php $tags->name(); ?></a>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>
    
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)): ?>
    <section class="sidebar-widget">
        <h3 class="widget-title">其他</h3>
        <ul class="widget-list">
            <?php if($this->user->hasLogin()): ?>
            <li class="widget-list-item">
                <a href="<?php $this->options->adminUrl(); ?>" title="进入后台"><i class="fas fa-cog"></i> 进入后台</a>
            </li>
            <li class="widget-list-item">
                <a href="<?php $this->options->logoutUrl(); ?>" title="退出登录"><i class="fas fa-sign-out-alt"></i> 退出登录</a>
            </li>
            <?php else: ?>
            <li class="widget-list-item">
                <a href="<?php $this->options->adminUrl('login.php'); ?>" title="登录"><i class="fas fa-sign-in-alt"></i> 登录</a>
            </li>
            <?php endif; ?>
            <li class="widget-list-item">
                <a href="<?php $this->options->feedUrl(); ?>" title="文章RSS"><i class="fas fa-rss"></i> 文章RSS</a>
            </li>
            <li class="widget-list-item">
                <a href="<?php $this->options->commentsFeedUrl(); ?>" title="评论RSS"><i class="fas fa-comments"></i> 评论RSS</a>
            </li>
        </ul>
    </section>
    <?php endif; ?>
</aside>
