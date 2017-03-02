                <?php if ($this->options['strict'] || (is_array($t->aContents)  || is_object($t->aContents))) foreach($t->aContents as $key => $oContent) {?><tr class="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'switchRowClass'))) echo htmlspecialchars($t->switchRowClass("","","alternateRow"));?>">
                    <td><input class="checkbox noBorder" type="checkbox" name="frmDelete[]" value="<?php echo htmlspecialchars($oContent->id);?>" /></td>
                    <td scope="row">
                        <a class="actionAlt" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","content","cms",$t->aContents,"frmContentId|id||cLang|langCode",$key));?>"><?php echo htmlspecialchars($oContent->name);?></a>
                        <?php if ($oContent->matchNumber)  {?> <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("matches"));?>: <?php echo htmlspecialchars($oContent->matchNumber);?><?php }?>
                    </td>
                    <td><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo $this->plugin("getExistingLanguageVersions",$oContent);?></td>
                    <td class="sortedColumn"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'formatDate'))) echo htmlspecialchars($t->formatDate($oContent->lastUpdated));?></td>
                    <td><?php echo htmlspecialchars($oContent->createdByName);?></td>
                    <td><?php echo htmlspecialchars($oContent->version);?></td>
                    <td nowrap="nowrap"><?php if ($this->options['strict'] || (isset($this) && method_exists($this, 'plugin'))) echo htmlspecialchars($this->plugin("getByKey",$t->aStatusTypes,$oContent->status));?></td>
                    <td>
                        <a class="action" href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("view","contentview","cms",$t->aContents,"frmContentId|id",$key));?>">View</a>
                    </td>
                    <td nowrap="nowrap">
                        <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("edit","linker","cms",$t->aContents,"frmContentId|id",$key));?>"><?php echo htmlspecialchars($oContent->numLinks);?> links</a>
                    </td>
                </tr><?php }?>
