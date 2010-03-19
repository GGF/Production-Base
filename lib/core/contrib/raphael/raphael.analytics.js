window.onload = function () {
    // Grab the data
    var labels = [],
        data = [];
    $("#data tfoot th").each(function () {
        labels.push($(this).html());
    });
    $("#data tbody td").each(function () {
        data.push($(this).html());
    });
    $("#data").hide();
    
    // Draw
    var width = 800,
        height = 250,
        leftgutter = 30,
        bottomgutter = 20,
        topgutter = 20,
        color = Raphael.hsb2rgb(Math.random(), 1, .75).hex,
        r = Raphael("holder", width, height),
        txt = {"font": '9px "Arial"', stroke: "none", fill: "#fff"},
        txt2 = {"font": '12px "Arial"', stroke: "none", fill: "#000"},
        X = (width - leftgutter) / labels.length,
        max = Math.max.apply(Math, data),
        Y = (height - bottomgutter - topgutter) / max;
    r.drawGrid(leftgutter + X * .5, topgutter, width - leftgutter - X, height - topgutter - bottomgutter, 10, 10, "#333");
    var path = r.path({stroke: color, "stroke-width": 4, "stroke-linejoin": "round"}),
        bgp = r.path({stroke: "none", fill: color, opacity: .2}).moveTo(leftgutter + X * .5, height - bottomgutter),
        dots = r.group(),
        cover = r.group(),
        frame = dots.rect(10, 10, 100, 40, 5).attr({fill: "#000", stroke: "#474747", "stroke-width": 2}).hide(),
        label = [],
        is_label_visible = false,
        leave_timer;
    label[0] = r.text(60, 25, "24 hits").attr(txt).hide();
    label[1] = r.text(60, 40, "22 September 2008").attr(txt).attr({fill: color}).hide();

    for (var i = 0, ii = labels.length; i < ii; i++) {
        var y = Math.round(height - bottomgutter - Y * data[i]),
            x = Math.round(leftgutter + X * (i + .5)),
            t = r.text(x, height - 6, labels[i]).attr(txt).toBack();
        bgp[i == 0 ? "lineTo" : "cplineTo"](x, y, 10);
        path[i == 0 ? "moveTo" : "cplineTo"](x, y, 10);
        var dot = dots.circle(x, y, 5).attr({fill: color, stroke: "#000"});
        var rect = r.rect(leftgutter + X * i, 0, X, height - bottomgutter).attr({stroke: "none", fill: "#fff", opacity: 0});
        (function (x, y, data, lbl, dot) {
            var timer, i = 0;
            $(rect[0]).hover(function () {
                clearTimeout(leave_timer);
                var newcoord = {x: x * 1 + 7.5, y: y - 19};
                if (newcoord.x + 100 > width) {
                    newcoord.x -= 114;
                }
                frame.show().animateTo(newcoord.x, newcoord.y, (is_label_visible ? 100 : 0));
                label[0].attr({text: data + " hit" + ((data % 10 == 1) ? "" : "s")}).show().animateTo(newcoord.x * 1 + 50, newcoord.y * 1 + 15, (is_label_visible ? 100 : 0));
                label[1].attr({text: lbl + " September 2008"}).show().animateTo(newcoord.x * 1 + 50, newcoord.y * 1 + 30, (is_label_visible ? 100 : 0));
                dot.attr("r", 7);
                is_label_visible = true;
                r.safari();
            }, function () {
                dot.attr("r", 5);
                r.safari();
                leave_timer = setTimeout(function () {
                    frame.hide();
                    label[0].hide();
                    label[1].hide();
                    is_label_visible = false;
                    r.safari();
                }, 1);
            });
        })(x, y, data[i], labels[i], dot);
    }
    bgp.lineTo(x, height - bottomgutter).andClose();
    frame.toFront();
};