<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:fo="http://www.w3.org/1999/XSL/Format">
    <xsl:output method="xml" indent="yes"/>
    <xsl:template match="root">
        <fo:root>
            <fo:layout-master-set>
                <fo:simple-page-master master-name="Portada" page-height="8.27in" page-width="11.69in"
                                       margin-top="0.5in" margin-bottom="0.15in" margin-left="0.65in"
                                       margin-right="0.35in">
                    <fo:region-body margin-top="0in" margin-bottom="0.5in"/>
                    <fo:region-after extent="0.38in"/>
                </fo:simple-page-master>
                <fo:simple-page-master master-name="PaginasIzquierdas" page-height="8.27in" page-width="11.69in"
                                       margin-top="0.5in" margin-bottom="0.15in" margin-left="0.65in"
                                       margin-right="0.35in">
                    <fo:region-body margin-top="0.7in" margin-bottom="0.5in"/>
                    <fo:region-before extent="0.43in"/>
                    <fo:region-after extent="0.38in"/>
                </fo:simple-page-master>
                <fo:simple-page-master master-name="PaginasDerechas" page-height="8.27in" page-width="11.69in"
                                       margin-top="0.5in" margin-bottom="0.15in" margin-left="0.65in"
                                       margin-right="0.35in">
                    <fo:region-body margin-top="0.7in" margin-bottom="0.5in"/>
                    <fo:region-before extent="0.43in"/>
                    <fo:region-after extent="0.38in"/>
                </fo:simple-page-master>
                <fo:page-sequence-master master-name="Contenido">
                    <fo:repeatable-page-master-alternatives>
                        <fo:conditional-page-master-reference page-position="first" master-reference="Portada"/>
                        <fo:conditional-page-master-reference page-position="rest" master-reference="PaginasIzquierdas"
                                                              odd-or-even="even"/>
                        <fo:conditional-page-master-reference page-position="rest" master-reference="PaginasDerechas"
                                                              odd-or-even="odd"/>
                    </fo:repeatable-page-master-alternatives>
                </fo:page-sequence-master>
            </fo:layout-master-set>

            <fo:page-sequence master-reference="Contenido">
                <fo:static-content flow-name="xsl-region-before">
                    <fo:table width="100%" block-progression-dimension="auto" table-layout="fixed"
                              font-family="MyriadWebPro, sans-serif">
                        <fo:table-column column-width="0.5in">
                        </fo:table-column>
                        <fo:table-column column-width="6.77in">
                        </fo:table-column>
                        <fo:table-body>
                            <fo:table-row font-size="8pt" text-transform="uppercase">
                                <fo:table-cell text-align="center">
                                    <fo:block margin-left="0.3em" margin-right="0.3em">
                                        <fo:external-graphic src="file:../../xsl/images/xabal.png" clear="none"
                                                             float="left" content-height="0.4in" scaling="uniform"
                                                             border="0" padding="0" margin="0"
                                                             alignment-baseline="middle"/>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell border-left-style="solid" border-left-width="0.007in"
                                               border-left-color="#cd870a">
                                    <fo:block margin-left="1em" margin-right="0.3em" padding-top="0.5em"
                                              alignment-baseline="middle">
                                        <fo:inline font-weight="bold">Sistema de Nivel de Acceso
                                        </fo:inline>
                                    </fo:block>
                                    <fo:block margin-left="1em" margin-right="0.3em" padding-top="0.75em"
                                              alignment-baseline="middle">
                                        <fo:inline>
                                            <xsl:value-of select="user/title_document"></xsl:value-of>
                                        </fo:inline>
                                    </fo:block>
                                </fo:table-cell>
                            </fo:table-row>
                        </fo:table-body>
                    </fo:table>
                </fo:static-content>
                <!-- Definición del pie de página -->
                <fo:static-content flow-name="xsl-region-after">
                    <fo:table width="100%" block-progression-dimension="auto" table-layout="fixed"
                              font-family="MyriadWebPro, sans-serif">
                        <fo:table-column column-width="40%"> <!--2.908in-->
                        </fo:table-column>
                        <fo:table-column column-width="35%"> <!--2.5445in-->
                        </fo:table-column>
                        <fo:table-column column-width="17%"> <!--1.0905in-->
                        </fo:table-column>
                        <fo:table-column column-width="8%"> <!--0.727in-->
                        </fo:table-column>
                        <fo:table-body>
                            <fo:table-row>
                                <fo:table-cell>
                                    <fo:block font-size="8pt" margin-left="0.3em" margin-right="0.3em">
                                        <fo:external-graphic src="file:../../xsl/images/setac.png" clear="none"
                                                             float="left" content-height="0.35in" scaling="uniform"
                                                             border="0" padding="0" margin="0"
                                                             alignment-baseline="middle"/>
                                        <fo:inline space-start="1em" font-weight="bold" text-transform="uppercase">
                                            Sistema de Nivel de Acceso
                                        </fo:inline>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell font-size="7pt">
                                    <fo:block margin-top="0.5em" border-left-style="solid" border-left-width="0.007in"
                                              border-left-color="#720000">
                                        <fo:block margin-left="0.3em" margin-right="0.3em" background-color="#f4f4f4">
                                            Generado por:
                                        </fo:block>
                                        <fo:block margin-top="0.5em" margin-left="0.3em" margin-right="0.3em"
                                                  font-weight="bold">
                                            <fo:inline>
                                                <xsl:value-of select="user/username"></xsl:value-of>
                                            </fo:inline>
                                        </fo:block>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell font-size="7pt">
                                    <fo:block margin-top="0.5em" border-left-style="solid" border-left-width="0.007in"
                                              border-left-color="#720000">
                                        <fo:block margin-left="0.3em" margin-right="0.3em" background-color="#f4f4f4">
                                            Fecha de creación:
                                        </fo:block>
                                        <fo:block margin-top="0.5em" margin-left="0.3em" margin-right="0.3em"
                                                  font-weight="bold">
                                            <fo:inline>
                                                <xsl:value-of select="user/create_date"></xsl:value-of>
                                            </fo:inline>
                                        </fo:block>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell font-size="7pt">
                                    <fo:block margin-top="0.5em" border-left-style="solid" border-left-width="0.007in"
                                              border-left-color="#720000">
                                        <fo:block margin-left="0.3em" margin-right="0.3em" background-color="#f4f4f4">
                                            Página:
                                        </fo:block>
                                        <fo:block margin-top="0.5em" margin-left="0.3em" margin-right="0.3em"
                                                  font-weight="bold">
                                            <fo:page-number/>
                                            de
                                            <fo:page-number-citation ref-id="LastPage"/>
                                        </fo:block>
                                    </fo:block>
                                </fo:table-cell>
                            </fo:table-row>
                        </fo:table-body>
                    </fo:table>
                </fo:static-content>

                <fo:flow flow-name="xsl-region-body">

                    <fo:block break-after="page" font-family="MyriadWebPro, sans-serif" font-size="11pt">
                        <xsl:call-template name="content_body"></xsl:call-template>
                        <fo:block id="LastPage"/>
                    </fo:block>

                </fo:flow>

            </fo:page-sequence>
        </fo:root>
    </xsl:template>

    <xsl:template name="cover_head">
        <!--Cabezal de portada-->
        <fo:block text-align="center" text-transform="uppercase" font-size="9pt" font-weight="bold">
            <fo:block>
                <fo:external-graphic src="file:../../xsl/images/setac.png" clear="none" float="left"
                                     content-height="1in" scaling="uniform" border="0" padding="0" margin="0 auto"
                                     alignment-baseline="middle"/>
            </fo:block>
            <fo:block margin-top="0.3em">Seminario Teológico Adventista de Cuba</fo:block>
            <fo:block margin-top="0.3em"></fo:block>
            <fo:block margin-top="0.3em">Sistema de Nivel de Acceso</fo:block>
        </fo:block>
        <fo:block margin-bottom="1.5em" text-align="left" font-family="MyriadWebPro, sans-serif"
                  text-transform="uppercase" font-weight="bold" margin-top="2.3em" border-bottom-style="solid"
                  border-bottom-width="0.007in" border-bottom-color="black">
            <xsl:value-of select="user/title_document"/>
        </fo:block>
        <!-- Fin cabezal de portada-->
    </xsl:template>

    <xsl:template name="filters_head">
        <!--Tabla de elementos sueltos-->
        <fo:table border-width="0in" table-layout="fixed" width="100%" border-collapse="separate"
                  border-separation="0.7em">
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-column column-width="5%">
            </fo:table-column>
            <fo:table-body>
                <xsl:if test="count(filters_header/element) &gt; 0">
                    <xsl:for-each select="filters_header/element">
                        <fo:table-row>
                            <xsl:for-each select="element">
                                <xsl:variable name="cols" select="cols"/>
                                <fo:table-cell number-columns-spanned="{$cols}">
                                    <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in"
                                                        border-bottom-color="#a0a0a0" border-right-style="solid"
                                                        border-right-width="0.005in" border-right-color="#a0a0a0">
                                        <fo:block font-size="7pt" background-color="#fbfbfb">
                                            <xsl:value-of select="label"/>
                                        </fo:block>
                                        <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em"
                                                  margin-right="0.3em" font-weight="bold">
                                            <xsl:value-of select="value"/>
                                        </fo:block>
                                    </fo:block-container>
                                </fo:table-cell>
                            </xsl:for-each>
                        </fo:table-row>
                    </xsl:for-each>
                </xsl:if>


            </fo:table-body>
        </fo:table>
        <!--Fin tabla de elementos sueltos-->
    </xsl:template>

    <xsl:template name="content_body">

        <xsl:call-template name="cover_head"></xsl:call-template>

        <xsl:if test="count(filters_header/element) &gt; 0">
            <xsl:call-template name="filters_head"></xsl:call-template>
        </xsl:if>

        <xsl:if test="count(data_elements/element) &gt; 0">
            <xsl:for-each select="data_elements/element">
            <!--<xsl:if test="count(data/element) &gt; 0">-->
                <!--Tabla de Contenidos-->
                <xsl:if test="count(data/element) &gt; 0">
                    <fo:table margin-top="2em" table-omit-footer-at-break="true" table-omit-header-at-break="false"
                              width="100%" block-progression-dimension="auto" table-layout="fixed" border-style="solid"
                              border-width="0.007in" border-color="#a0a0a0" text-align="left" font-size="9pt"
                              color="#3d3d3d">
                        <fo:table-column column-width="4%"></fo:table-column>
                        <!--Columnas dinamicas -->
                        <!--Columnas dinamicas -->
                        <xsl:for-each select="header/column/element">
                            <xsl:variable name="width" select="width"/>
                            <fo:table-column column-width='{$width}'/>
                        </xsl:for-each>
                        <!--<fo:table-column column-width="40%"></fo:table-column>-->
                        <!--Fin Columnas dinamicas -->
                        <fo:table-header>

                            <xsl:variable name="spanned_col" select="count(header/column/element)"/>
                            <fo:table-row font-size="11pt" font-weight="bold" height="20px" line-height="20px"
                                          border-top-width="1px" border-top-color="#a0a0a0">
                                <!--Titulo de la tabla spanned debe ser dinamico-->
                                <fo:table-cell number-columns-spanned="{$spanned_col}">
                                    <fo:block margin-left="3px">
                                        <xsl:value-of select="table_title"></xsl:value-of>
                                    </fo:block>
                                </fo:table-cell>
                            </fo:table-row>
                            <fo:table-row font-size="11pt" background-color="#5ab8ec" height="24px"
                                          line-height="24px" font-weight="bold" border-top-width="1px"
                                          border-top-color="#a0a0a0" border-bottom-width="1px"
                                          border-bottom-color="#fad7a5" alignment-baseline="middle">
                                <xsl:choose>
                                    <xsl:when test="count(header/last/element) &gt; 0">
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4"
                                                       border-left-color="#a0a0a0" border-top-color="#a0a0a0" number-rows-spanned="2" display-align="center">
                                            <fo:block margin-left="3px"  >Nº</fo:block>
                                        </fo:table-cell>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4"
                                                       border-left-color="#a0a0a0" border-top-color="#a0a0a0">
                                            <fo:block margin-left="3px">Nº</fo:block>
                                        </fo:table-cell>
                                    </xsl:otherwise>
                                </xsl:choose>
                                <xsl:for-each select="header/first/element">
                                    <!--Contenido dinamico-->
                                    <xsl:variable name="column_spanned" select="column_spanned"/>
                                    <xsl:variable name="row_spanned" select="row_spanned"/>
                                    <fo:table-cell border-style="solid" border-width="0.007in"
                                                   border-color="#f4f4f4" border-top-color="#a0a0a0">
                                        <xsl:if test="position() = last()">
                                            <xsl:attribute name="border-right-color">#a0a0a0</xsl:attribute>
                                        </xsl:if>
                                        <xsl:if test="$column_spanned > 1">
                                            <xsl:attribute name="text-align">center</xsl:attribute>
                                            <xsl:attribute name="number-columns-spanned"><xsl:value-of select="column_spanned"/></xsl:attribute>
                                        </xsl:if>
                                        <xsl:if test="$row_spanned > 1">
                                            <xsl:attribute name="display-align">center</xsl:attribute>
                                            <xsl:attribute name="number-rows-spanned"><xsl:value-of select="row_spanned"/></xsl:attribute>
                                        </xsl:if>
                                        <fo:block margin-left="3px">
                                            <xsl:value-of select="column_title"></xsl:value-of>
                                        </fo:block>
                                    </fo:table-cell>
                                    <!--Fin Contenido dinamico-->
                                </xsl:for-each>
                            </fo:table-row>
                            <xsl:if test="count(header/last/element) &gt; 0">
                                <fo:table-row font-size="11pt" background-color="#5ab8ec" height="24px"
                                              line-height="24px" font-weight="bold" border-top-width="1px"
                                              border-top-color="#a0a0a0" border-bottom-width="1px"
                                              border-bottom-color="#fad7a5" alignment-baseline="middle">
                                    <xsl:for-each select="header/last/element">
                                        <xsl:variable name="column_spanned" select="column_spanned"/>
                                        <xsl:variable name="row_spanned" select="row_spanned"/>
                                        <fo:table-cell border-style="solid" border-width="0.007in"
                                                       border-color="#f4f4f4" border-top-color="#a0a0a0">
                                            <xsl:if test="position() = last()">
                                                <xsl:attribute name="border-right-color">#a0a0a0</xsl:attribute>
                                            </xsl:if>
                                            <xsl:if test="$column_spanned > 1">
                                                <xsl:attribute name="text-align">center</xsl:attribute>
                                                <xsl:attribute name="number-columns-spanned"><xsl:value-of select="column_spanned"/></xsl:attribute>
                                            </xsl:if>
                                            <xsl:if test="$row_spanned > 1">
                                                <xsl:attribute name="display-align">center</xsl:attribute>
                                                <xsl:attribute name="number-rows-spanned"><xsl:value-of select="row_spanned"/></xsl:attribute>
                                            </xsl:if>
                                            <fo:block margin-left="3px">
                                                <xsl:value-of select="column_title"></xsl:value-of>
                                            </fo:block>
                                        </fo:table-cell>
                                    </xsl:for-each>
                                </fo:table-row>
                            </xsl:if>
                        </fo:table-header>
                        <!--Se puede condicionar si se muestra o no-->
                        <fo:table-footer>
                            <fo:table-row font-size="11pt" font-weight="bold" height="30px" line-height="30px"
                                          border-top-width="1px" border-top-color="#a0a0a0">
                                <!--Resumen de la tabla spanned debe ser dinamico -->
                                <xsl:variable name="spanned_col" select="count(header/element)"/>
                                <!--<fo:table-cell>
                                    <fo:block margin-left="5px"></fo:block>
                                </fo:table-cell>-->
                                <!--Puede ser un valor directo o el total de elementos iterados-->
                                <fo:table-cell number-columns-spanned="{$spanned_col}+1" border-style="solid"
                                               border-width="0.007in" border-color="#a0a0a0">
                                    <fo:block margin-right="3px" text-align="right">
                                        <!--<xsl:value-of select='count(data/element)'></xsl:value-of>-->
                                    </fo:block>
                                </fo:table-cell>
                            </fo:table-row>
                        </fo:table-footer>

                        <fo:table-body>
                            <xsl:for-each select="data/element">
                                <xsl:variable name="data_element" select="position()"/>
                                <fo:table-row height="20px" line-height="20px" background-color="#e5e5e5">
                                    <xsl:choose>
                                        <xsl:when test="position() mod 2 = 0">
                                            <xsl:attribute name="background-color">#fbfbfb</xsl:attribute>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:attribute name="background-color">#ffffff</xsl:attribute>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <!--Se puede condicionar si se muestra o no-->
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4"
                                                   border-left-color="#a0a0a0" border-bottom-color="#a0a0a0">
                                        <fo:block margin-left="3px">
                                            <xsl:value-of select="position()"></xsl:value-of>
                                        </fo:block>
                                    </fo:table-cell>
                                    <xsl:for-each select="*">
                                        <fo:table-cell border-style="solid" border-width="0.007in"
                                                       border-color="#f4f4f4" border-bottom-color="#a0a0a0">
                                            <xsl:if test="position() = last()">
                                                <xsl:attribute name="border-right-color">#a0a0a0</xsl:attribute>
                                            </xsl:if>
                                            <fo:block margin-left="3px">
                                                <xsl:value-of select="."/>
                                            </fo:block>
                                        </fo:table-cell>
                                    </xsl:for-each>
                                </fo:table-row>
                            </xsl:for-each>
                        </fo:table-body>
                    </fo:table>
                </xsl:if>
                <!--</xsl:if>-->
    </xsl:for-each>
</xsl:if>
<!--Fin contenido especifico del documento-->
    </xsl:template>


</xsl:stylesheet>
