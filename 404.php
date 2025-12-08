<?php
/**
 * 404错误页模板
 * 
 * @package SteamAnnouncement
 */

$this->need('header.php');
?>

<main class="main-content">
    <div class="container">
        <div class="error-404">
            <div class="error-code">404</div>
            <h1 class="error-title">页面未找到</h1>
            <p class="error-description">
                抱歉，您访问的页面不存在或已被删除。
            </p>
            <div class="error-actions">
                <a href="<?php $this->options->siteUrl(); ?>" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    返回首页
                </a>
                <a href="javascript:history.back()" class="btn">
                    <i class="fas fa-arrow-left"></i>
                    返回上一页
                </a>
            </div>
            
            <div class="error-search">
                <h3>搜索一下？</h3>
                <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                    <input type="text" name="s" placeholder="搜索文章..." class="search-input">
                    <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php $this->need('footer.php'); ?>
