    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-book"></i> إ<span style="color: #FBBF24;">بداع</span>
                    </h5>
                    <p class="text-muted">منصة عربية لنشر الإبداع والكتابة والتصميم</p>
                    <div class="mt-3">
                        <a href="#" class="text-muted me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">الروابط</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-muted text-decoration-none">الرئيسية</a></li>
                        <li><a href="/books.php" class="text-muted text-decoration-none">الكتب</a></li>
                        <li><a href="/designs.php" class="text-muted text-decoration-none">التصاميم</a></li>
                        <li><a href="/creators.php" class="text-muted text-decoration-none">المبدعين</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">المساعدة</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">الدعم</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">سياسة الخصوصية</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">الشروط والأحكام</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="mb-3">النشرة البريدية</h5>
                    <p class="text-muted small">اشترك للحصول على آخر الأخبار والإبداعات</p>
                    <form>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="بريدك الإلكتروني" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <hr class="bg-secondary">
            
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small">© 2024 إبداع - جميع الحقوق محفوظة</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted small">
                        تم التطوير بـ <i class="fas fa-heart text-danger"></i> من قبل فريق إبداع
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/assets/js/main.js"></script>
</body>
</html>
