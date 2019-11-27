<? php
    function tm_event_page_tickets ( $event, $globals ) {

        $tm_output .= '
        
            <%= form_tag url_for(controller: :shop, action: :to_cart, format: :js), remote: true, id: :ticket_select_form, :data => { :toggle => 'validator' } do %>
                
                <% if @event.state[:prices_shown] %>
                    <%= hidden_field_tag :bundle_discount, params[:event_discount], :disabled => true if params[:event_discount].present? %>
                    <%= hidden_field_tag :event_id, @event.id %>
                    <%= hidden_field_tag :applicable, false, :disabled => true %>

                    <% unless @event.categories.select{|ec| ec.ec_price >= 0}.empty? %>
                        <div class="form card mb-3" data-validation="validateTicketsnumselectForm">
                            <div class="table-header">
                                <div class="row">
                                    <div class="<% if @event.state["sale_active"] %>col-5<% else %>col-8 col-sm-9<% end %>">
                                        <%= custom_locale("webshop.select_unseated.table2.row1") %>
                                    </div>

                                    <div class="col-4 col-sm-3 <% if !@event.state["sale_active"] %>text-right<% end %>">
                                        <%= custom_locale("webshop.select_unseated.table2.row2") %>
                                    </div>

                                    <% if @event.state["sale_active"] %>
                                        <div class="col-3 col-sm-2">
                                            <%= custom_locale("webshop.select_unseated.table2.row3") %>
                                        </div>

                                        <div class="col-2 d-none d-sm-block text-right">
                                            <span><%= custom_locale("webshop.select_unseated.table2.row4") %></span>
                                        </div>
                                    <% end %>
                                </div>
                            </div>

                            <div class="table-body">
                                <%= render custom_view( "ticket_table_body" ) %>
                            </div>
                            
                            <% if @event.state["sale_active"] %>
                                <div class="table-footer">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <%= custom_locale("webshop.userdata.order_history.total") %>: &nbsp;
                                            <span id="tickets_price_sum" class="primary-text"><%= custom_currency(0.00) %></span>
                                        </div>
                                    </div>
                                </div>
                            <% end %>

                        </div>
                    <% end %>
                <% end %>

                <div>
                    <%= link_to :back, :class => "btn btn-secondary px-3" do %>
                        <i class="fas fa-chevron-left"></i> &nbsp;<%= custom_locale("webshop.check_out.billinginfo.back") %>
                    <% end %>

                    <% if @event.state[:prices_shown] && @event.state["sale_active"] %>
                        <% unless @event.categories.select{|ec| ec.ec_price > 0}.empty? %>
                            <%= button_tag(:class => "btn btn-primary float-right px-3", :type=>"submit", :onclick => 'Validate("#ticket_select_form");', :id => "add_to_cart") do %>
                                <%= custom_locale("webshop.body.to_basket") %> &nbsp;<i class="far fa-arrow-alt-circle-right"></i>
                            <% end %>
                        <% end %>
                    <% end %>
                </div>

            <% end %>
        ';

        return $tm_output;
	}
?>