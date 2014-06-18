jQuery('document').ready(
function($){
    $('input.js-quantity').each(function(){
            
            var $this = $(this);
            
            var value = Number($this.attr('value'));
            var step =  $this.data('step') ? $this.data('step') : 1;

            
            var parent = $this.parent();
            var isMinusPlus = false;
            var plus = null, minus=null;
            var children = $this.parent().children('button.btn');
            if (children.length === 2){
                //plus and minus might already be existing, no need of adding
                minus = children.eq(0); 
                plus  =  children.eq(1);
                isMinusPlus=true;
            }
            if (!isMinusPlus){
                $this.wrap('<div />');
                parent.addClass('input-prepend').addClass('input-append input-group');

                var minus = $('<button class="btn btn-primary input-group-addon">-</button>');
                var plus = $('<button class="btn btn-primary input-group-addon">+</button>');
            }
            minus.on('click.quantity', function(e){
                    e.preventDefault();
                    min = $this.data('min') ? $this.data('min') : 0;
                    if(value <= min) return;
                    $this.attr('value', value = value - step).change();
            });

            
            plus.on('click.quantity', function(e){
                    e.preventDefault();
                    if($this.data('max') && value >= $this.data('max')) return;
                    $this.attr('value', value = value + step).change();
            })

            $this.before(minus).after(plus);
    });

    $('input.js-quantity, [data-max], [data-min]').each(function(){
            var $this = $(this);
            var $input = $this.is('input') ? $this : $this.find('input');
            $input.on('keyup.value', function(e) {$(this).change();})
            $input.on('change.value', function(e) {
                    value = Number($input.attr('value'));	
                    changed = false;
                    min = $this.data('min') ? $this.data('min') : 0;
                    if(value < min) {
                            value = min; 
                            changed = true;
                            $input.attr('value', value);	
                    }

                    if($this.data('max') && value > $this.data('max')) {
                            value = $this.data('max'); 
                            changed = true;

                    }
                    if(!changed) return;

                    if($this.data('currency')) {
                            value = parseFloat(value); 
                            value = value.toFixed(2);	
                    }

                    $input.attr('value', value);
            });
    });
    });