/*
  Ordered List
  from: https://github.com/madebymany/sir-trevor-blocks/blob/master/src/ordered-list.js
*/

SirTrevor.Blocks.OrderedList = (function() {

  var template = _.template('<div class="st-text-block" contenteditable="true"><ol><li></li></ol></div>');

  return SirTrevor.Block.extend({

    type: "ordered_list",

    title: "Ordered List",

    icon_name: 'list',

    editorHTML: function() {
      return template(this);
    },

    loadData: function(data){
      this.getTextBlock().html("<ol>" + SirTrevor.toHTML(data.text, this.type) + "</ol>");
    },

    onBlockRender: function() {
      this.checkForList = this.checkForList.bind(this);
      this.getTextBlock().on('click keyup', this.checkForList);
      this.focus();
    },

    checkForList: function() {
      if (this.$('ol').length === 0) {
        document.execCommand("insertOrderedList", false, false);
      }
    },

    toMarkdown: function(markdown) {
      return markdown.replace(/<\/li>/mg,"\n")
                     .replace(/<\/?[^>]+(>|$)/g, "")
                     .replace(/^(.+)$/mg," 1. $1");
    },

    toHTML: function(html) {
      html = html.replace(/^ 1. (.+)$/mg,"<li>$1</li>")
                 .replace(/\n/mg, "");

      return html;
    },

    onContentPasted: function(event, target) {
      this.$('ol').html(
        this.pastedMarkdownToHTML(target[0].innerHTML));
      this.getTextBlock().caretToEnd();
    },

    isEmpty: function() {
      return _.isEmpty(this.getBlockData().text);
    }
  });

})();