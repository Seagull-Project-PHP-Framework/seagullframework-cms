<script type="text/javascript">

var aElems = new Array();

function pushListElems()
{
    var listElementName = $('listElementName');
    var listElementValue = $('listElementValue');
    if (listElementName.value != '' && listElementValue.value != '') {
        var elementList = $('elementList');

        //  test if elementList key already exists
        var options = elementList.getElementsByTagName('option');
        options = $A(options);
        var duplicateExists = options.find( function(listItem) {
            if (listItem.value != '') {
                aData = listItem.value.split("|");
                return (aData[0] == listElementName.value)
            }
        });
        if (typeof duplicateExists != 'undefined') {
            alert('Attribute list names must be unique');
            return false;
        }
        //  update combobox
        var separatorOpt = ' => ';
        var separatorElem = '|';
        var optValue = listElementName.value + separatorElem + listElementValue.value;

        var optName = listElementName.value + separatorOpt + listElementValue.value;
        var opt = Builder.node('option', optName);
        opt.value = optValue;

        elementList.appendChild(opt);
        //  for querystring as selecting all options surprisingly doesn't work
        aElems.push(optValue);
    } else {
        alert('Please enter a name and a value');
    }
}
</script>

<div id="response"></div>

<form id="newAttribList" method="post" action="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("addAttribList","","cms"));?>">

    <!-- ADD ATTRIB TYPE NAME AND VALUES -->
    <div id="attribListNameAndValues">
        <fieldset class="noBorder">
            <dl class="onTop">
                <dt><label for="elemList_name"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Attribute type name"));?></label></dt>
                <dd>
                    <?php if ($t->error['name'])  {?><div class="error"><?php echo htmlspecialchars($t->error['name']);?></div><?php }?>
                    <input type="text" class="text" name="elemList[name]" id="elemList_name" />
                </dd>
                <dd>
                    <dl>
                        <dt>Name</dt>
                        <dd><input type="text" id="listElementName" /></dd>
                    </dl>
                    <dl>
                        <dt>Value</dt>
                        <dd><input type="text" id="listElementValue" /></dd>
                    </dl>
                    <dl>
                        <dt>&nbsp;</dt>
                        <dd><input type="button" value="add" onclick="pushListElems();" /></dd>
                    </dl>
                </dd>
            </dl>
        </fieldset>
    </div>

    <!--POPULATED NAME VALUE PAIRS-->
    <div id="attribListNameValuePairs">
        <fieldset class="noBorder">
            <dl class="onTop">
                <dt><label for="">Elements</label></dt>
                <dd>
                    <select name="elemList[elems]" id="elementList" multiple="multiple" size="7">
                        <option value="">------------------</option>
                    </select>
                </dd>
            </dl>
        </fieldset>
    </div>

    <div class="clear"></div>

    <!--SAVE LIST-->
    <div>
        <dl>
            <dd>
                <input type="button" value=" save list " onclick="
                    cms.contentType.attributeList.save();" />
            </dd>
        </dl>
    </div>
</form>