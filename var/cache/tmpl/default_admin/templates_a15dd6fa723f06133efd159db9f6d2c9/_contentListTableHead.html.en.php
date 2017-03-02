                            <tr>
                                <th width="3%">
        	                        <span class="tipOwner">
        		                        <?php echo $this->elements['checkAll']->toHtml();?>
        		                        <span class="tipText" id="becareful"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'translate'))) echo htmlspecialchars($t->translate("Be Careful!"));?></span>
        	                        </span>
                                </th>
                                <th width="45%" scope="col">
        	                        <?php if ($t->sort_name)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|sortBy||sortOrder|sortOrderDisplay"));?>" title="Sort by name">Content Name</a><?php }?>
        	                        <?php if (!$t->sort_name)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|name||sortOrder|asc"));?>" title="Sort by name">Content Name</a><?php }?>
                                </th>
                                <th>Language</th>
                                <th nowrap="nowrap" scope="col" class="sortedColumn sortedAsc">
                                    <?php if ($t->sort_last_updated)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|sortBy||sortOrder|sortOrderDisplay"));?>" title="Sort by date">Last Updated</a><?php }?>
                                    <?php if (!$t->sort_last_updated)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|last_updated||sortOrder|asc"));?>" title="Sort by date">Last Updated</a><?php }?>
                                </th>
                                <th nowrap="nowrap" scope="col">
                                    <?php if ($t->sort_created_by_id)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|sortBy||sortOrder|sortOrderDisplay"));?>" title="Sort by author">Author</a><?php }?>
                                    <?php if (!$t->sort_created_by_id)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|created_by_id||sortOrder|asc"));?>" title="Sort by author">Author</a><?php }?>
                                </th>
                                <th nowrap="nowrap" scope="col">
                                    Version
                                </th>
                                <th nowrap="nowrap" scope="col">
                                    <?php if ($t->sort_status)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|sortBy||sortOrder|sortOrderDisplay"));?>" title="Sort by status">Status</a><?php }?>
                                    <?php if (!$t->sort_status)  {?><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","content","cms","","sortBy|status||sortOrder|asc"));?>" title="Sort by status">Status</a><?php }?>
                                </th>
                                <th nowrap="nowrap" scope="col" class="action">Actions</th>
                                <th nowrap="nowrap" scope="col" class="action">Links</th>
                            </tr>