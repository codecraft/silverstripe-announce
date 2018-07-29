<div id="$Name" data-dismissable="$CanDismiss">
    <% if $Title %><p>$Title</p><% end_if %>
    <% if $Heading %>
        <div class="heading">
            $Heading
        </div>
    <% end_if %>
    <div class="content">
        $Content
    </div>
    <% if $Footer || $Actions %>
        <div class="footer">
            $Footer
            <div class="actions">
                <% if $Actions %>
                    $Actions
                <% end_if %>
            </div>
        </div>
    <% end_if %>
</div>
