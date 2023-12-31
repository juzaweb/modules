<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ids }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ $ids }}');
</script>