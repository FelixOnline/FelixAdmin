/*
  Modified version of default cite block to remove some validation
*/

SirTrevor.Blocks.Quote = (function() {

  return SirTrevor.Block.extend({

    type: "quote",

    title: "Quote",

    icon_name: 'quote',

    editorHTML: function() {
	      return _.template([
		  '<blockquote class="st-required st-text-block" contenteditable="true"></blockquote>',
		  '<label class="st-input-label">Credit</label>',
		  '<input maxlength="140" name="cite" placeholder="Credit"',
		  ' class="st-input-string js-cite-input" type="text" />'
		].join("\n"))(this);
    },

	loadData: function(data){
		this.getTextBlock().html(SirTrevor.toHTML(data.text, this.type));
	},

	toMarkdown: function(markdown) {
		return markdown.replace(/^(.+)$/mg,"> $1");
	}

  });

})();