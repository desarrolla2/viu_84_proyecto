SOURCE := ./source
OUTPUT := ./output
TEMPLATE := ./templates

SOURCE_SUBDIRECTORIES := $(wildcard $(SOURCE)/*)
MARKDOWN_FILES := $(foreach dir,$(SOURCE_SUBDIRECTORIES),$(wildcard $(dir)/*.md))
PDF_FILES := $(patsubst $(SOURCE)/%,$(OUTPUT)/%,$(MARKDOWN_FILES:.md=.pdf))

.PHONY: clean build

build: $(PDF_FILES)

$(OUTPUT)/%.pdf: $(SOURCE)/%.md
	@mkdir -p $(dir $@)
	@if [ -f $(TEMPLATE)/$(notdir $(basename $<)).tex ]; then \
		echo " + Generating PDF with template for $<"; \
		pandoc $< -o $@ --template=$(TEMPLATE)/$(notdir $(basename $<)).tex --pdf-engine=xelatex; \
	else \
		echo " - Generating PDF without template for $<"; \
		pandoc $< -o $@ --pdf-engine=xelatex; \
	fi

clean:
	@rm -rf $(OUTPUT)/*
