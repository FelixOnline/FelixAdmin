SirTrevor.Blocks.Factoid = (function() {
  var template = _.template([
    '<div class="st-required st-text-block" contenteditable="true"></div>',
    '<label class="st-input-label"> Statistic caption</label>',
    '<input name="description" style="width: 100%" placeholder="Number of cats in the world"',
    ' class="st-input-string st-required js-description-input" type="text" />'
  ].join("\n"));

  return SirTrevor.Block.extend({

    type: "factoid",

    title: "Factoid",

    icon_name: 'quote',

    editorHTML: function() {
      return template(this);
    },

    loadData: function(data){
      this.getTextBlock().html(SirTrevor.toHTML(data.text, this.type));
      this.$('.js-description-input').val(data.description);
    },

    toMarkdown: function(markdown) {
      return markdown.replace(/^(.+)$/mg,"$1");
    }

  });
}) ();