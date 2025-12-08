    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <p>&copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>. All rights reserved.</p>
                    <p>Powered by <a href="http://typecho.org" target="_blank">Typecho</a> | Theme <a href="#" target="https://github.com/govmoe/steam-announcement-Template">SteamAnnouncement</a></p>
                </div>
                
                <div class="footer-links">
                    <ul class="social-links">
                        <?php if ($this->options->steamUrl): ?>
                        <li><a href="<?php $this->options->steamUrl(); ?>" class="social-link" target="_blank"><i class="fab fa-steam"></i></a></li>
                        <?php endif; ?>
                        <?php if ($this->options->twitterUrl): ?>
                        <li><a href="<?php $this->options->twitterUrl(); ?>" class="social-link" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <?php endif; ?>
                        <?php if ($this->options->githubUrl): ?>
                        <li><a href="<?php $this->options->githubUrl(); ?>" class="social-link" target="_blank"><i class="fab fa-github"></i></a></li>
                        <?php endif; ?>
                        <?php if ($this->options->discordUrl): ?>
                        <li><a href="<?php $this->options->discordUrl(); ?>" class="social-link" target="_blank"><i class="fab fa-discord"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- 引入JavaScript文件 -->
    <script src="<?php $this->options->themeUrl('js/script.js'); ?>"></script>
    
    <?php $this->footer(); ?>
</body>
</html>
