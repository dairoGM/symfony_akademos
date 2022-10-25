<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
    <xsl:output method="xml" indent="yes" />
    <xsl:template match="root">
        <fo:root>
            <!-- Definición de layouts para las páginas-->
            <fo:layout-master-set>
                <fo:simple-page-master master-name="Portada" page-height="8.27in" page-width="11.69in" margin-top="0.5in" margin-bottom="0.15in" margin-left="0.65in" margin-right="0.35in">
                    <fo:region-body margin-top="0in" margin-bottom="0.5in" />
                    <fo:region-after extent="0.38in" />
                </fo:simple-page-master>
                <fo:simple-page-master master-name="PaginasIzquierdas" page-height="8.27in" page-width="11.69in" margin-top="0.5in" margin-bottom="0.15in" margin-left="0.65in" margin-right="0.35in">
                    <fo:region-body margin-top="0.7in" margin-bottom="0.5in" />
                    <fo:region-before extent="0.43in" />
                    <fo:region-after extent="0.38in" />
                </fo:simple-page-master>
                <fo:simple-page-master master-name="PaginasDerechas" page-height="8.27in" page-width="11.69in" margin-top="0.5in" margin-bottom="0.15in" margin-left="0.65in" margin-right="0.35in">
                    <fo:region-body margin-top="0.7in" margin-bottom="0.5in" />
                    <fo:region-before extent="0.43in" />
                    <fo:region-after extent="0.38in" />
                </fo:simple-page-master>
                <fo:page-sequence-master master-name="Contenido">
                    <fo:repeatable-page-master-alternatives>
                        <fo:conditional-page-master-reference page-position="first" master-reference="Portada" />
                        <fo:conditional-page-master-reference page-position="rest" master-reference="PaginasIzquierdas" odd-or-even="even" />
                        <fo:conditional-page-master-reference page-position="rest" master-reference="PaginasDerechas" odd-or-even="odd" />
                    </fo:repeatable-page-master-alternatives>
                </fo:page-sequence-master>
            </fo:layout-master-set>
            <!-- Definición de la secuencia para el cabezal-->
            <fo:page-sequence master-reference="Contenido">
                <fo:static-content flow-name="xsl-region-before">
                    <fo:table width="100%" block-progression-dimension="auto" table-layout="fixed" font-family="MyriadWebPro, sans-serif">
                        <fo:table-column column-width="0.5in">
                        </fo:table-column>
                        <fo:table-column column-width="10.19in">
                        </fo:table-column>
                        <fo:table-body>
                            <fo:table-row font-size="8pt" text-transform="uppercase">
                                <fo:table-cell text-align="center">
                                    <fo:block margin-left="0.3em" margin-right="0.3em">
                                        <fo:external-graphic src="file:images/insignia_angola_clipped.jpg" clear="none" float="left" content-height="0.4in" scaling="uniform" border="0" padding="0" margin="0" alignment-baseline="middle"/>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell border-left-style="solid" border-left-width="0.007in" border-left-color="#cd870a">
                                    <fo:block margin-left="1em" margin-right="0.3em" padding-top="0.5em" alignment-baseline="middle">
                                        <fo:inline font-weight="bold">Sistema de Nivel de Acceso</fo:inline>
                                    </fo:block>
                                    <fo:block margin-left="1em" margin-right="0.3em" padding-top="0.75em" alignment-baseline="middle">
                                        <fo:inline>
                                            <xsl:value-of select="nombre_documento"></xsl:value-of>
                                        </fo:inline>
                                    </fo:block>
                                </fo:table-cell>
                            </fo:table-row>
                        </fo:table-body>
                    </fo:table>
                </fo:static-content>
                <!-- Definición del pie de página -->
                <fo:static-content flow-name="xsl-region-after">
                    <fo:table width="100%" block-progression-dimension="auto" table-layout="fixed" font-family="MyriadWebPro, sans-serif">
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
                                        <fo:external-graphic src="file:images/bolsas.png" clear="none" float="left" content-height="0.35in" scaling="uniform" border="0" padding="0" margin="0" alignment-baseline="middle"/>
                                        <fo:inline space-start="1em" font-weight="bold" text-transform="uppercase">Sistema de Nivel de Acceso</fo:inline>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell font-size="7pt">
                                    <fo:block margin-top="0.5em" border-left-style="solid" border-left-width="0.007in" border-left-color="#720000">
                                        <fo:block margin-left="0.3em" margin-right="0.3em" background-color="#f4f4f4">Generado por:</fo:block>
                                        <fo:block margin-top="0.5em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                            <fo:inline>
                                                <xsl:value-of select="generado_por"></xsl:value-of>.
                                            </fo:inline>
                                        </fo:block>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell font-size="7pt">
                                    <fo:block margin-top="0.5em" border-left-style="solid" border-left-width="0.007in" border-left-color="#720000">
                                        <fo:block margin-left="0.3em" margin-right="0.3em" background-color="#f4f4f4">Fecha de creación:</fo:block>
                                        <fo:block margin-top="0.5em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                            <fo:inline>
                                                <xsl:value-of select="data_creacion"></xsl:value-of>.
                                            </fo:inline>
                                        </fo:block>
                                    </fo:block>
                                </fo:table-cell>
                                <fo:table-cell font-size="7pt">
                                    <fo:block margin-top="0.5em" border-left-style="solid" border-left-width="0.007in" border-left-color="#720000">
                                        <fo:block margin-left="0.3em" margin-right="0.3em" background-color="#f4f4f4">Página:</fo:block>
                                        <fo:block margin-top="0.5em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                            <fo:page-number /> de <fo:page-number-citation ref-id="LastPage"/>
                                        </fo:block>
                                    </fo:block>
                                </fo:table-cell>
                            </fo:table-row>
                        </fo:table-body>
                    </fo:table>
                </fo:static-content>
                <!--Definción de la secuencia del flujo del cuerpo del documento-->
                <fo:flow flow-name="xsl-region-body">
                    <fo:block font-family="MyriadWebPro, sans-serif" font-size="11pt">
                        <!--Cabezal de portada-->
                        <fo:block text-align="center" text-transform="uppercase" font-size="9pt" font-weight="bold">
                            <fo:block>
                                <fo:external-graphic src="file:images/insignia_angola_clipped.jpg" clear="none" float="left" content-height="1in" scaling="uniform" border="0" padding="0" margin="0 auto" alignment-baseline="middle"/>
                            </fo:block>
                            <fo:block margin-top="0.3em">Seminario Teológico Adventista de Cuba</fo:block>
                            <fo:block margin-top="0.3em"></fo:block>
                            <fo:block margin-top="0.3em">Sistema de Nivel de Acceso </fo:block>
                        </fo:block>
                        <fo:block margin-bottom="1.5em" text-align="left" font-family="MyriadWebPro, sans-serif" text-transform="uppercase" font-weight="bold" margin-top="2.3em" border-bottom-style="solid"  border-bottom-width="0.007in" border-bottom-color="black">
                            <xsl:apply-templates select="nombre_documento"/>
                        </fo:block>
                        <!-- Fin cabezal de portada-->
                        <!--Contenido especifico del documento-->
                        <!--Tabla de elementos sueltos-->
                        <fo:table border-width="0in" table-layout="fixed" width="100%" border-collapse="separate" border-separation="0.7em">
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
                                <fo:table-row>
                                    <fo:table-cell number-columns-spanned="6">
                                        <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in" border-bottom-color="#a0a0a0" border-right-style="solid" border-right-width="0.005in" border-right-color="#a0a0a0">
                                            <fo:block font-size="7pt" background-color="#fbfbfb">Período</fo:block>
                                            <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                                <xsl:apply-templates select="periodo"/>
                                            </fo:block>                                
                                        </fo:block-container>                                        
                                    </fo:table-cell>
                                    <fo:table-cell number-columns-spanned="8">
                                        <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in" border-bottom-color="#a0a0a0" border-right-style="solid" border-right-width="0.005in" border-right-color="#a0a0a0">
                                            <fo:block font-size="7pt" background-color="#fbfbfb">Oferta</fo:block>
                                            <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                                <xsl:apply-templates select="oferta"/>
                                            </fo:block>                                
                                        </fo:block-container>                                                                                
                                    </fo:table-cell>
                                    <fo:table-cell number-columns-spanned="6"> <!--el ultimo campo no lleva margen derecho-->
                                        <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in" border-bottom-color="#a0a0a0" border-right-style="solid" border-right-width="0.005in" border-right-color="#a0a0a0">
                                            <fo:block font-size="7pt" background-color="#fbfbfb">Nível</fo:block>
                                            <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                                <xsl:apply-templates select="nivel"/>
                                            </fo:block>                                
                                        </fo:block-container>                                                                                
                                    </fo:table-cell>
                                </fo:table-row>
                                <fo:table-row> 
                                    <fo:table-cell number-columns-spanned="5">
                                        <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in" border-bottom-color="#a0a0a0" border-right-style="solid" border-right-width="0.005in" border-right-color="#a0a0a0">
                                            <fo:block font-size="7pt" background-color="#fbfbfb">Período</fo:block>
                                            <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                                <xsl:apply-templates select="periodo"/>
                                            </fo:block>                                
                                        </fo:block-container>                                        
                                    </fo:table-cell>
                                    <fo:table-cell number-columns-spanned="7">
                                        <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in" border-bottom-color="#a0a0a0" border-right-style="solid" border-right-width="0.005in" border-right-color="#a0a0a0">
                                            <fo:block font-size="7pt" background-color="#fbfbfb">Oferta</fo:block>
                                            <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                                <xsl:apply-templates select="oferta"/>
                                            </fo:block>                                
                                        </fo:block-container>                                                                                
                                    </fo:table-cell>
                                    <fo:table-cell number-columns-spanned="8">
                                        <fo:block-container border-bottom-style="solid" border-bottom-width="0.005in" border-bottom-color="#a0a0a0" border-right-style="solid" border-right-width="0.005in" border-right-color="#a0a0a0">
                                            <fo:block font-size="7pt" background-color="#fbfbfb">Nível</fo:block>
                                            <fo:block font-size="9pt" margin-top="0.3em" margin-left="0.3em" margin-right="0.3em" font-weight="bold">
                                                <xsl:apply-templates select="nivel"/>
                                            </fo:block>                                
                                        </fo:block-container>                                                                                
                                    </fo:table-cell>
                                </fo:table-row>
                            </fo:table-body>
                        </fo:table>
                        <!--Fin tabla de elementos sueltos-->
                        <!--Tabla de Contenidos-->
                        <fo:table margin-top="1.5em" table-omit-footer-at-break="true" table-omit-header-at-break="false" width="100%" block-progression-dimension="auto" table-layout="fixed" border-style="solid" border-width="0.007in" border-color="#a0a0a0" text-align="left" font-size="9pt" color="#3d3d3d">
                            <fo:table-column column-width="5%">
                            </fo:table-column>
                            <fo:table-column column-width="38%">
                            </fo:table-column>
                            <fo:table-column column-width="12%">
                            </fo:table-column>
                            <fo:table-column column-width="6%">
                            </fo:table-column>
                            <fo:table-column column-width="14%">
                            </fo:table-column>
                            <fo:table-column column-width="25%">
                            </fo:table-column>
                            <fo:table-header>
                                <fo:table-row font-size="11pt" background-color="#5ab8ec" height="24px" line-height="24px" font-weight="bold" border-top-width="1px" border-top-color="#a0a0a0" border-bottom-width="1px" border-bottom-color="#fad7a5" alignment-baseline="middle">
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-left-color="#a0a0a0" border-top-color="#a0a0a0">
                                        <fo:block margin-left="5px">No.</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-top-color="#a0a0a0">
                                        <fo:block margin-left="5px">Nome Completo</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-top-color="#a0a0a0">
                                        <fo:block margin-left="5px">No do BI</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-top-color="#a0a0a0">
                                        <fo:block margin-left="5px">Sexo</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-top-color="#a0a0a0">
                                        <fo:block margin-left="5px">Província</fo:block>
                                    </fo:table-cell>
                                    <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-right-color="#a0a0a0" border-top-color="#a0a0a0">
                                        <fo:block margin-left="5px">Curso</fo:block>
                                    </fo:table-cell>
                                </fo:table-row>
                            </fo:table-header>
                            <fo:table-footer>
                                <fo:table-row font-size="11pt" font-weight="bold" height="30px" line-height="30px"  border-top-width="1px" border-top-color="#a0a0a0">
                                    <fo:table-cell number-columns-spanned="6">
                                        <fo:block margin-left="5px">
                                            Total de registros: <xsl:value-of select="count(becarios/becario)"></xsl:value-of>
                                        </fo:block>
                                    </fo:table-cell>
                                </fo:table-row>                                
                            </fo:table-footer>
                            <fo:table-body>
                                <xsl:for-each select="becarios/becario">
                                    <fo:table-row height="20px" line-height="20px"  background-color="#e5e5e5">
                                        <xsl:choose>
                                            <xsl:when test="position() mod 2 = 0">
                                                <xsl:attribute name="background-color">#fbfbfb</xsl:attribute>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <xsl:attribute name="background-color">#ffffff</xsl:attribute>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-left-color="#a0a0a0" border-bottom-color="#a0a0a0">
                                            <fo:block margin-left="5px">
                                                <xsl:value-of select="position()"></xsl:value-of>
                                            </fo:block>
                                        </fo:table-cell>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-bottom-color="#a0a0a0">
                                            <fo:block margin-left="5px">
                                                <xsl:apply-templates select="nombre"/>
                                            </fo:block>
                                        </fo:table-cell>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-bottom-color="#a0a0a0">
                                            <fo:block margin-left="5px">
                                                <xsl:apply-templates select="bi"/>
                                            </fo:block>
                                        </fo:table-cell>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-bottom-color="#a0a0a0">
                                            <fo:block margin-left="5px">
                                                <xsl:apply-templates select="sexo"/>
                                            </fo:block>
                                        </fo:table-cell>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-bottom-color="#a0a0a0">
                                            <fo:block margin-left="5px">
                                                <xsl:apply-templates select="provincia"/>
                                            </fo:block>
                                        </fo:table-cell>
                                        <fo:table-cell border-style="solid" border-width="0.007in" border-color="#f4f4f4" border-right-color="#a0a0a0" border-bottom-color="#a0a0a0">
                                            <fo:block margin-left="5px">
                                                <xsl:apply-templates select="curso"/>
                                            </fo:block>
                                        </fo:table-cell>
                                    </fo:table-row>
                                </xsl:for-each>
                            </fo:table-body>
                        </fo:table>                        
                        <!--Fin contenido especifico del documento-->
                        <!--Marca para contar páginas-->
                        <fo:block id="LastPage"></fo:block>
                    </fo:block>
                </fo:flow>
            </fo:page-sequence>
        </fo:root>
    </xsl:template>
</xsl:stylesheet>
