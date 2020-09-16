</div>
<footer>
    <div class="footer">
        <p>&copy; <?= date('Y') ?> Nike, Inc. Все права защищены</p>
    </div>
</footer>
<script type="text/javascript" src="/template/js/jquery.js"></script>
<script>
    function changeStatus(id) {
        $.ajax({
            type: "POST",
            url: "/admin/order/ajaxUpdate",
            data: {orderStatusParams: id},
        });
    }
</script>
</body>
</html>
