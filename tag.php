<?php
/**
 * 标签页模板
 * 
 * @package SteamAnnouncement
 */

// 导入侧边导航菜单解析函数
function parseSidebarMenu($menuConfig) {
    $menu = array();
    $lines = explode("\n", $menuConfig);
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $parts = explode('|', $line, 2);
        if (count($parts) == 2) {
            $menu[] = array(
                'name' => trim($parts[0]),
                'url' => trim($parts[1])
            );
        }
    }
    
    return $menu;
}

$this->need('header.php');
?>

<div class="page-wrapper">
    <!-- 左侧导航 - Steam风格 -->
    <nav class="main-navigation">
        <ul class="nav-menu">
            <?php
            // 获取侧边导航菜单配置
            $menuConfig = $this->options->sidebarMenu;
            $menuItems = parseSidebarMenu($menuConfig);
            
            foreach ($menuItems as $item) {
                $isActive = false;
                $isGroupTitle = ($item['url'] == '#');
                
                // 检查是否为当前页面
                if (!$isGroupTitle) {
                    $currentUrl = $this->request->getPathinfo();
                    $itemUrl = parse_url($item['url'], PHP_URL_PATH);
                    $isActive = ($currentUrl == $itemUrl);
                }
                
                if ($isGroupTitle) {
                    // 分组标题
                    echo '<li class="nav-item"><span class="nav-group-title">' . $item['name'] . '</span></li>';
                } else {
                    // 普通菜单项
                    echo '<li class="nav-item"><a href="' . $item['url'] . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . $item['name'] . '</a></li>';
                }
            }
            
            // 显示Typecho页面
            $this->widget('Widget_Contents_Page_List')->to($pages);
            if ($pages->have()) {
                echo '<li class="nav-item"><span class="nav-group-title">页面</span></li>';
                while($pages->next()):
                    $isActive = ($this->is('page') && $this->cid == $pages->cid);
                    echo '<li class="nav-item"><a href="' . $pages->permalink() . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . $pages->title() . '</a></li>';
                endwhile;
            }
            ?>
        </ul>
    </nav>
    
    <!-- 主内容区 -->
    <div class="content-container">
        <main class="main-content">
            <div class="container">
                <div class="archive-header">
                    <h1 class="archive-title">
                        标签：<?php $this->archiveTitle(array('tag' => '%s'), '', ''); ?>
                    </h1>
                    <p class="archive-description">
                        共包含 <?php $this->archiveCount(); ?> 篇文章
                    </p>
                </div>
                
                <div class="announcement-list">
                    <?php if ($this->have()): ?>
                        <?php while($this->next()): ?>
                        <article class="announcement-card">
                            <div class="announcement-header">
                                <h2 class="announcement-title">
                                    <a href="<?php $this->permalink() ?>" title="<?php $this->title() ?>"><?php $this->title() ?></a>
                                </h2>
                                <div class="announcement-meta">
                                    <span class="announcement-date"><?php $this->date('Y-m-d H:i'); ?></span>
                                    <span class="announcement-author"><?php $this->author(); ?></span>
                                    <span class="announcement-category">
                                        <?php $this->category(', '); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="announcement-excerpt">
                                <?php $this->content('阅读更多...'); ?>
                            </div>
                            
                            <div class="announcement-footer">
                                <a href="<?php $this->permalink() ?>" class="read-more">阅读更多</a>
                                <div class="announcement-tags">
                                    <?php $this->tags(' ', true, '无标签'); ?>
                                </div>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-posts">
                            <h3>暂无文章</h3>
                            <p>该标签下还没有发布任何文章</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="pagination">
                    <?php $this->pageNav('上一页', '下一页', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'page-nav', 'itemTag' => 'span', 'textTag' => 'a', 'currentClass' => 'current')); ?>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>
