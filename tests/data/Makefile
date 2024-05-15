SOURCE_DIR := ./source
OUTPUT_DIR := ./output

SUBDIRS := $(wildcard $(SOURCE_DIR)/*)

MD_FILES := $(foreach dir,$(SUBDIRS),$(wildcard $(dir)/*.md))

PDF_FILES := $(patsubst $(SOURCE_DIR)/%,$(OUTPUT_DIR)/%,$(MD_FILES:.md=.pdf))

.PHONY: all clean

all: $(PDF_FILES)

$(OUTPUT_DIR)/%.pdf: $(SOURCE_DIR)/%.md
	@mkdir -p $(dir $@)
	pandoc $< -o $@

clean:
	@rm -rf $(OUTPUT_DIR)/*
