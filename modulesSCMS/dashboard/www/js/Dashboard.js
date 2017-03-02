Dashboard = {}
Dashboard.Widget =
{
    init: function() {
		var _self = this;
	
		$('.widget-col').Sortable({
			accept:      'widget-item',
			handle:      '.widget-header',			
			helperclass: 'widget-helper',
			tolerance:   'pointer',
			
//	    	activeclass: 'sortableactive',
//		    hoverclass:  'sortablehover',
            containment: 'document',
			
			onStart: function() {
				$.iAutoscroller.start(this, document.getElementsByTagName('body'));
			},
			onStop: function() {
				$.iAutoscroller.stop();
			    $.ajax({
			        url: SGL2.Util.makeUrl({module: 'dashboard', action: 'updateOrdering'}),
			        data: _self.serialize()
			    });				
			}
		});
    },
    
    serialize: function() {
		var oCols = $.SortSerialize().o;
		var data = '';
		for (var columnId in oCols) {
			for (var i in oCols[columnId]) {
				if (data) {
					data += '&';
				}						
				data += columnId + '[]=';
				if ($('#' + oCols[columnId][i]).attr('sgl:widget')) {
				    data += 'widget-' + $('#' + oCols[columnId][i]).attr('sgl:widget');
				}
			}
		}
		return data;
    }
}