<script type="text/template" id="dltmpl_map_info_window">
    <a href="<%- permalink %>">
        <div class="dlmap-info-window__header">
            <img class="dlmap-info-window__thumbnail" src="<%- thumbnail.image.thumbnail %>" alt=""/>
            <h3 class="dlmap-info-window__title"><%- title %></h3>
        </div>
        <ul class="dlmap-info-window__meta">
            <li class="dlmap-info-window__meta-address">
                <%- extra.date %>
            </li>
            <li class="dlmap-info-window__meta-address">
                <%- location.address %>
            </li>
        </ul>
    </a>
</script>