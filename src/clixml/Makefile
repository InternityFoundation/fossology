# Copyright Siemens AG 2021
#
# Copying and distribution of this file, with or without modification,
# are permitted in any medium without royalty provided the copyright
# notice and this notice are preserved.  This file is offered as-is,
# without any warranty.

TOP = ../..
VARS = $(TOP)/Makefile.conf
include $(VARS)

MOD_NAME = clixml

DIRS = agent ui
TESTDIRS = agent_tests

DIR_LOOP = @set -e; for dir in $(DIRS); do $(MAKE) -s -C $$dir $(1); done
TESTDIR_LOOP = @set -e; for dir in $(TESTDIRS); do $(MAKE) -s -C $$dir $(1); done

all: VERSIONFILE
	$(call DIR_LOOP, )

test: all
	$(call TESTDIR_LOOP,test)

coverage: all
	@echo "nothing to do"

VERSIONFILE:
	$(call WriteVERSIONFile,$(MOD_NAME))

install: all
	$(call DIR_LOOP,install)
	$(INSTALL_DATA) VERSION $(DESTDIR)$(MODDIR)/$(MOD_NAME)/VERSION
	$(INSTALL_DATA) $(MOD_NAME).conf $(DESTDIR)$(MODDIR)/$(MOD_NAME)/$(MOD_NAME).conf
	mkdir -p $(DESTDIR)$(SYSCONFDIR)/mods-enabled
	if test ! -e $(DESTDIR)$(SYSCONFDIR)/mods-enabled/$(MOD_NAME); then \
		ln -s $(MODDIR)/$(MOD_NAME) $(DESTDIR)$(SYSCONFDIR)/mods-enabled; \
	fi
uninstall:
	$(call DIR_LOOP,uninstall)
	rm -rf $(DESTDIR)$(MODDIR)/$(MOD_NAME)
	rm -f $(DESTDIR)$(SYSCONFDIR)/mods-enabled/$(MOD_NAME)
clean:
	$(call DIR_LOOP,clean)
	rm -f VERSION

.PHONY: all test coverage VERSIONFILE install uninstall clean
