<% if $Type == 'button' || $Type == 'default' %>
    <button $AttributesHTML>$Content</button>
<% else_if $Type == 'anchor' %>
    <a $AttributesHTML>$Content</a>
<% end_if %>
