
initD3Graph = ->
    frFormatter = d3.locale
        "decimal": ","
        "thousands": " "
        "grouping": [3]
        "currency": ["", "EUR"]
        "dateTime": "%a %b %e %X %Y"
        "date": "%d/%m/%Y"
        "time": "%H:%M:%S"
        "periods": []
        "days": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
        "shortDays": ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
        "months": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
        "shortMonths": ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]

    d3.format = frFormatter.numberFormat
    
    margin = {top: 20, right: 20, bottom: 50, left: 50}
    width = $('.graph').width() - margin.left - margin.right
    height = 300 - margin.top - margin.bottom
    
    x = d3.time.scale().range([0, width])
    
    y = d3.scale.linear().range([height, 0]);
    
    xAxis = d3.svg.axis()
            .scale(x)
            .ticks(4)
            .tickSize(-height)
            .tickSubdivide(true)
            .orient("bottom")
    
    yAxis = d3.svg.axis()
            .scale(y)
            .ticks(6)
            .tickSize(-width)
            .tickSubdivide(true)
            .orient("left");
    
    line = d3.svg.line()
            .x( (d) ->
                x(d.date)
            )
            .y( (d) ->
                y(d.value)
            )
    
    svg = d3.select(".graph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");


    # data here
    data_a = $('.graph').data 'type-a'
    data_m = $('.graph').data 'type-m'
    
    parseDate = d3.time.format("%Y%m").parse

    data_a.forEach( (d) ->
        d.date = parseDate(d.date)
        d.value = +d.value
    )
    data_m.forEach( (d) ->
        d.date = parseDate(d.date)
        d.value = +d.value
    )

    min_max_f = (a,b,field) ->
        min_max_a = d3.extent( a, (d) -> d[field] )
        min_max_b = d3.extent( b, (d) -> d[field] )
        [ d3.min( [ min_max_a[0], min_max_b[0] ] ), d3.max( [ min_max_a[1], min_max_b[1] ] ) ]
    x.domain( min_max_f( data_a, data_m, 'date' ) )
    min_max = min_max_f( data_a, data_m, 'value' )
    min_max[0] = min_max[0] - (min_max[1] - min_max[0]) / 3;
    min_max[1] = min_max[1] + (min_max[1] - min_max[0]) / 3;
    y.domain( min_max );

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis)
        
    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis)
        
        
    svg.append("path")
        .datum(data_a)
        .attr("class", "line type_a")
        .attr("d", line);

    svg.append("path")
        .datum(data_m)
        .attr("class", "line type_m")
        .attr("d", line);


    legend = svg.selectAll(".legend")
        .data( [ { t: 'm', l: $('.graph').data('legend-type-m') }, { t: 'a', l: $('.graph').data('legend-type-a') } ] )
        .enter().append("g")
        .attr("class", "legend")
        .attr("transform", (d, i) -> "translate(" + i * 100 +  ",0)" )

    legend.append("rect")
        .attr("x", 0)
        .attr('y', height + margin.top + 5 )
        .attr("width", 18)
        .attr("height", 18)
        .attr( 'class', (d) -> 'type_' + d.t )

    legend.append("text")
        .attr("x", 0)
        .attr("y", height + margin.top + 5 )
        .attr("dx", "25px")
        .attr("dy", ".8em")
        .style("text-anchor", "start")
        .text( (d) -> d.l )

initEstimationMap = ->
    #console.log "ready, so mapping"
    map_div = $('body.estimation .map')
    loc = map_div.data().loc
    map_center = new google.maps.LatLng parseFloat( loc.lat ), parseFloat( loc.lon )
    map = new google.maps.Map map_div[0],
        center: map_center
        mapTypeId: google.maps.MapTypeId.ROADMAP
        zoom: 12
#    window.vl_map = map

    class PriceOverlay extends google.maps.OverlayView
        constructor: (@map,@center,@rel_pos,@div_id) ->
            this.setMap @map
            @width = 200
            @height = 120
        onAdd: ->
            @div = $(@div_id)
            @div.width @width + 'px'
            @div.height @height + 'px'
            @div.css 'position', 'absolute'
            this.getPanes().overlayLayer.appendChild( @div[0] )
        draw: ->
            overlayProjection = this.getProjection()
            center_px = overlayProjection.fromLatLngToDivPixel map_center
            base_spread = 80
            left = center_px.x + @rel_pos * base_spread + ( if @rel_pos < 0 then - @width else 0 )
            @div.css 'left', left + 'px'
            @div.css 'top', (center_px.y - @height / 2) + 'px'
        onRemove: ->
            @div[0].parentNode.removeChild @div[0]
            @div = null
    overlay_config = '#overlay_price_a': -1 , '#overlay_price_m': 1
    for id, pos of overlay_config
        if $(id).length > 0 
            new PriceOverlay map, map_center, pos, id


$ ->
    map_div = $('body.estimation .map')
    if map_div.length > 0 and map_div.is(':visible')
        window.vl_if_map_ready ->
            initEstimationMap()
            
    if $('body.estimation .graph').length > 0
        initD3Graph()
