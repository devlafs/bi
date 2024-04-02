<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class CubosBeeController extends Controller {

    public function actionAtividade($id_conexao) {
        parent::stdout("Configurando o Cubo Padrão do Bee - Atividades\n\n", Console::BG_BLUE);

        $sql_indicador = <<<SQL
    
            INSERT INTO `bpbi_indicador` (`id_conexao`,`tipo`,`nome`,`descricao`,`sql`,`periodicidade`) VALUES ({$id_conexao},'database','Bee Padrão - Atividades','','SELECT\n	DISTINCT LTRIM(SUBSTRING_INDEX(solicitacao.caminhoCategoria,\n	\'/\',\n	-1)) AS `Categoria`,\n	solicitacao.codigo AS `Codigo da solicitacao`,\n	solicitacao.id AS `ID da solicitacao`,\n	solicitacao.nomeProcesso AS `Nome do processo`,\n	solicitacao.descricao AS `Descricao do processo`,\n	solicitacao.status AS `Status da solicitacao`,\n	solicitacao.condicao AS `Status do tempo da solicitacao`,\n	solicitacao.marco AS `Marco da solicitacao`,\n	solicitacao.prioridade AS `Prioridade da solicitacao`,\n	CASE\n		WHEN solicitacao.finalizacaoSucesso = \'Nao\' THEN \'Nao\'\n		WHEN solicitacao.finalizacaoSucesso IS NOT NULL THEN \'Nao\'\n		ELSE \'Sim\'\n	END AS `Finalizado com Sucesso`,\n	CASE\n		WHEN solicitacao.status = \'Concluida\' THEN \'Concluida\'\n		ELSE solicitacao.tempo\n	END AS `Tempo da solicitacao`,\n	custo.valorTotalGeral AS `Valor Total Geral`,\n	custo.valorTotalReceitaPrevista AS `Valor previsto de Receita`,\n	custo.valorTotalReceitaConsolidada AS `Valor consolidado de Receita`,\n	custo.valorTotalDespesaPrevista AS `Valor previsto de Despesa`,\n	custo.valorTotalDespesaConsolidada AS `Valor consolidado de Despesa`,\n	custo.valorHorasServico AS `Valor de Horas de Servico`,\n	atividade.nome AS `Nome da atividade`,\n	atividade.status AS `Status da atividade`,\n	CASE\n		WHEN atividade.status = \'Concluida\' THEN \'Concluida\'\n		ELSE atividade.tempo\n	END AS `Tempo da atividade`,\n	atividade.ultimoAndamento AS `Ultimo andamento da atividade`,\n	atividade.condicao AS `Status de tempo da atividade`,\n	usuarioObjeto.identificador AS `Identificador do objeto`,\n	usuarioObjeto.nome AS `Nome do objeto`,\n	objeto.empresa AS `Empresa do objeto`,\n	pessoalObjeto.cidade AS `Cidade do objeto`,\n	pessoalObjeto.estado AS `Estado do objeto`,\n	usuarioObjeto.rota AS `Rota do objeto`,\n	usuarioObjeto.cargo AS `Cargo do objeto`,\n	usuarioObjeto.departamento AS `Departamento do objeto`,\n	usuarioObjeto.profissao AS `Profissao do objeto`,\n	pessoalObjeto.setor AS `Setor do objeto`,\n	usuarioDesignado.nome AS `Nome do designado`,\n	designado.empresa AS `Empresa do designado`,\n	pessoalDesignado.cidade AS `Cidade do designado`,\n	pessoalDesignado.estado AS `Estado do designado`,\n	usuarioDesignado.rota AS `Rota do designado`,\n	usuarioDesignado.cargo AS `Cargo do designado`,\n	usuarioDesignado.departamento AS `Departamento do designado`,\n	usuarioDesignado.profissao AS `Profissao do designado`,\n	pessoalDesignado.setor AS `Setor do designado`,\n	usuarioCadastradoPor.nome AS `Nome do cadastradoPor`,\n	cadastradoPor.empresa AS `Empresa do cadastradoPor`,\n	pessoalCadastradoPor.cidade AS `Cidade do cadastradoPor`,\n	pessoalCadastradoPor.estado AS `Estado do cadastradoPor`,\n	usuarioCadastradoPor.rota AS `Rota do cadastradoPor`,\n	usuarioCadastradoPor.cargo AS `Cargo do cadastradoPor`,\n	usuarioCadastradoPor.departamento AS `Departamento do cadastradoPor`,\n	usuarioCadastradoPor.profissao AS `Profissao do cadastradoPor`,\n	pessoalCadastradoPor.setor AS `Setor do cadastradoPor`,\n	usuarioExecutor.nome AS `Nome do executor`,\n	executor.empresa AS `Empresa do executor`,\n	pessoalExecutor.cidade AS `Cidade do executor`,\n	pessoalExecutor.estado AS `Estado do executor`,\n	usuarioExecutor.rota AS `Rota do executor`,\n	usuarioExecutor.cargo AS `Cargo do executor`,\n	usuarioExecutor.departamento AS `Departamento do executor`,\n	usuarioExecutor.profissao AS `Profissao do executor`,\n	pessoalExecutor.setor AS `Setor do executor`,\n	condicaoAtividade.valor AS `Pergunta da condicao`,\n	condicaoSelecionada.tipo AS `Resposta selecionada`,\n	regra.nome AS `Pergunta da regra`,\n	regra.ordem AS `Ordem da regra`,\n	regra.atendida AS `Resposta da regra`,\n	prazoSolicitacao.dataCadastro AS `DATA de cadastro da solicitacao`,\n	YEAR(prazoSolicitacao.dataCadastro) AS `Ano do cadastro da solicitacao`,\n	MONTH(prazoSolicitacao.dataCadastro) AS `Mes do cadastro da solicitacao`,\n	DAY(prazoSolicitacao.dataCadastro) AS `Dia do cadastro da solicitacao`,\n	CASE\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Monday\' THEN \'Segunda-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Tuesday\' THEN \'Terca-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Wednesday\' THEN \'Quarta-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Thursday\' THEN \'Quinta-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Friday\' THEN \'Sexta-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Saturday\' THEN \'Sabado\'\n		WHEN DAYNAME(prazoSolicitacao.dataCadastro) = \'Sunday\' THEN \'Domingo\'\n	END AS `Nome do dia do cadastro da solicitacao`,\n	DAYOFMONTH(prazoSolicitacao.dataCadastro) AS `Semana do ano do cadastro da solicitacao`,\n	(WEEK(DATE_FORMAT(prazoSolicitacao.dataCadastro,\n	\'%Y/%m/%d\')) - WEEK(DATE_FORMAT(prazoSolicitacao.dataCadastro,\n	\'%Y/%m/%01\'))) + 1 AS `Dia da semana da abertura da solicitacao`,\n	prazoSolicitacao.dataConclusao AS `DATA de conclusao da solicitacao`,\n	YEAR(prazoSolicitacao.dataConclusao) AS `Ano do conclusao da solicitacao`,\n	MONTH(prazoSolicitacao.dataConclusao) AS `Mes do conclusao da solicitacao`,\n	DAY(prazoSolicitacao.dataConclusao) AS `Dia do conclusao da solicitacao`,\n	CASE\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Monday\' THEN \'Segunda-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Tuesday\' THEN \'Terca-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Wednesday\' THEN \'Quarta-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Thursday\' THEN \'Quinta-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Friday\' THEN \'Sexta-Feira\'\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Saturday\' THEN \'Sabado\'\n		WHEN DAYNAME(prazoSolicitacao.dataConclusao) = \'Sunday\' THEN \'Domingo\'\n	END AS `Nome do dia da conclusao da solicitacao`,\n	DAYOFMONTH(prazoSolicitacao.dataConclusao) AS `Semana do ano conclusao da solicitacao`,\n	(WEEK(DATE_FORMAT(prazoSolicitacao.dataConclusao,\n	\'%Y/%m/%d\')) - WEEK(DATE_FORMAT(prazoSolicitacao.dataConclusao,\n	\'%Y/%m/%01\'))) + 1 AS `Dia da semana da conclusao da solicitacao`,\n	prazoAtividade.dataCadastro AS `DATA de cadastro da atividade`,\n	YEAR(prazoAtividade.dataCadastro) AS `Ano do cadastro da atividade`,\n	MONTH(prazoAtividade.dataCadastro) AS `Mes do cadastro da atividade`,\n	DAY(prazoAtividade.dataCadastro) AS `Dia do cadastro da atividade`,\n	CASE\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Monday\' THEN \'Segunda-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Tuesday\' THEN \'Terca-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Wednesday\' THEN \'Quarta-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Thursday\' THEN \'Quinta-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Friday\' THEN \'Sexta-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Saturday\' THEN \'Sabado\'\n		WHEN DAYNAME(prazoAtividade.dataCadastro) = \'Sunday\' THEN \'Domingo\'\n	END AS `Nome do dia do cadastro da atividade`,\n	DAYOFMONTH(prazoAtividade.dataCadastro) AS `Semana do ano do cadastro da atividade`,\n	(WEEK(DATE_FORMAT(prazoAtividade.dataCadastro,\n	\'%Y/%m/%d\')) - WEEK(DATE_FORMAT(prazoAtividade.dataCadastro,\n	\'%Y/%m/%01\'))) + 1 AS `Dia da semana da abertura da atividade`,\n	prazoAtividade.dataConclusao AS `DATA de conclusao da atividade`,\n	YEAR(prazoAtividade.dataConclusao) AS `Ano do conclusao da atividade`,\n	MONTH(prazoAtividade.dataConclusao) AS `Mes do conclusao da atividade`,\n	DAY(prazoAtividade.dataConclusao) AS `Dia do conclusao da atividade`,\n	CASE\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Monday\' THEN \'Segunda-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Tuesday\' THEN \'Terca-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Wednesday\' THEN \'Quarta-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Thursday\' THEN \'Quinta-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Friday\' THEN \'Sexta-Feira\'\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Saturday\' THEN \'Sabado\'\n		WHEN DAYNAME(prazoAtividade.dataConclusao) = \'Sunday\' THEN \'Domingo\'\n	END AS `Nome do dia da conclusao da atividade`,\n	DAYOFMONTH(prazoAtividade.dataConclusao) AS `Semana do ano conclusao da atividade`,\n	(WEEK(DATE_FORMAT(prazoAtividade.dataConclusao,\n	\'%Y/%m/%d\')) - WEEK(DATE_FORMAT(prazoAtividade.dataConclusao,\n	\'%Y/%m/%01\'))) + 1 AS `Dia da semana da conclusao da atividade`,\n    1 as Quantidade,\n    atividade.id as identificadorAtividade \n FROM\n	pr_solicitacao solicitacao\nINNER JOIN pr_solicitacao_atividade atividade ON\n	atividade.processo_id = solicitacao.id\nLEFT JOIN pr_solicitacao_custo custo ON\n	custo.processo_id = solicitacao.id\nINNER JOIN pr_solicitacao_entidade objeto ON\n	objeto.processo_id = solicitacao.id\n	AND objeto.tipoObjeto = \'Objeto\'\nLEFT JOIN admin_usuario usuarioObjeto ON\n	usuarioObjeto.id = objeto.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalObjeto ON\n	pessoalObjeto.id = usuarioObjeto.id\nLEFT JOIN pr_solicitacao_entidade designado ON\n	designado.processo_id = solicitacao.id\n	AND designado.tipoObjeto = \'Designado\'\nLEFT JOIN admin_usuario usuarioDesignado ON\n	usuarioDesignado.id = designado.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalDesignado ON\n	pessoalDesignado.id = usuarioDesignado.id\nLEFT JOIN pr_solicitacao_entidade cadastradoPor ON\n	cadastradoPor.processo_id = solicitacao.id\n	AND cadastradoPor.tipoObjeto = \'CadastradoPor\'\nLEFT JOIN admin_usuario usuarioCadastradoPor ON\n	usuarioCadastradoPor.id = cadastradoPor.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalCadastradoPor ON\n	pessoalCadastradoPor.id = usuarioCadastradoPor.id\nLEFT JOIN pr_solicitacao_entidade executor ON\n	executor.processo_id = atividade.entidade_id\nLEFT JOIN admin_usuario usuarioExecutor ON\n	usuarioExecutor.id = executor.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalExecutor ON\n	pessoalExecutor.id = usuarioExecutor.id\nLEFT JOIN pr_solicitacao_avaliacao condicaoAtividade ON\n	condicaoAtividade.tarefa_id = atividade.id\nLEFT JOIN pr_solicitacao_avaliacao_item condicaoSelecionada ON\n	condicaoSelecionada.avaliacao_id = condicaoAtividade.id\n	AND condicaoSelecionada.selecionado = \'S\'\nLEFT JOIN pr_solicitacao_regra regra ON\n	regra.tarefa_id = atividade.id\nLEFT JOIN pr_solicitacao_prazo prazoSolicitacao ON\n	prazoSolicitacao.id = solicitacao.prazo_id\nLEFT JOIN pr_solicitacao_prazo prazoAtividade ON\n	prazoAtividade.id = atividade.prazo_id','86400');
                
SQL;

        Yii::$app->db->createCommand($sql_indicador)->execute();

        $id_indicador = Yii::$app->db->createCommand("SELECT id FROM bpbi_indicador WHERE nome = 'Bee Padrão - Atividades'")->queryScalar();

        parent::stdout("Código do indicador: {$id_indicador}\n\n", Console::BG_GREEN);

        $sql_campos = <<<SQL
    
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},1,'Categoria','texto','Categoria',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},2,'Código da Solicitação','texto','Codigo da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},3,'Identificador da Solicitação','texto','ID da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},4,'Nome do Processo','texto','Nome do processo','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},5,'Descrição do Processo','texto','Descricao do processo','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},6,'Status da Solicitação','texto','Status da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},7,'Status do Tempo da Solicitação','texto','Status do tempo da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},8,'Marco da Solicitação','texto','Marco da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},9,'Prioridade da Solicitação','texto','Prioridade da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},10,'Finalizado com Sucesso','texto','Finalizado com Sucesso',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},11,'Tempo da Solicitação','texto','Tempo da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},12,'Valor Total Geral','valor','Valor Total Geral','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},13,'Valor Previsto de Receita','valor','Valor previsto de Receita','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},14,'Valor Consolidado de Receita','valor','Valor consolidado de Receita','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},15,'Valor Previsto de Despesa','valor','Valor previsto de Despesa','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},16,'Valor Consolidado de Despesa','valor','Valor consolidado de Despesa','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},17,'Valor de Horas de Serviço','valor','Valor de Horas de Servico','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},18,'Nome da Atividade','texto','Nome da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},19,'Status da Atividade','texto','Status da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},20,'Tempo da Atividade','texto','Tempo da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},21,'Últ. Andamento da Atividade','texto','Ultimo andamento da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},22,'Status de Tempo da Atividade','texto','Status de tempo da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},23,'Identificador do Objeto','texto','Identificador do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},24,'Nome do Objeto','texto','Nome do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},25,'Empresa do Objeto','texto','Empresa do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},26,'Cidade do Objeto','texto','Cidade do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},27,'Estado do Objeto','texto','Estado do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},28,'Rota do Objeto','texto','Rota do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},29,'Cargo do Objeto','texto','Cargo do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},30,'Departamento do Objeto','texto','Departamento do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},31,'Profissão do Objeto','texto','Profissao do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},32,'Setor do Objeto','texto','Setor do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},33,'Nome do Designado','texto','Nome do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},34,'Empresa do Designado','texto','Empresa do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},35,'Cidade do Designado','texto','Cidade do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},36,'Estado do Designado','texto','Estado do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},37,'Rota do Designado','texto','Rota do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},38,'Cargo do Designado','texto','Cargo do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},39,'Departamento do Designado','texto','Departamento do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},40,'Profissão do Designado','texto','Profissao do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},41,'Setor do Designado','texto','Setor do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},42,'Nome do Cadastrante','texto','Nome do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},43,'Empresa do Cadastrante','texto','Empresa do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},44,'Cidade do Cadastrante','texto','Cidade do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},45,'Estado do Cadastrante','texto','Estado do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},46,'Rota do Cadastrante','texto','Rota do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},47,'Cargo do Cadastrante','texto','Cargo do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},48,'Departamento do Cadastrante','texto','Departamento do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},49,'Profissão do Cadastrante','texto','Profissao do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},50,'Setor do Cadastrante','texto','Setor do cadastradoPor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},51,'Nome do Executor','texto','Nome do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},52,'Empresa do Executor','texto','Empresa do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},53,'Cidade do Executor','texto','Cidade do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},54,'Estado do Executor','texto','Estado do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},55,'Rota do Executor','texto','Rota do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},56,'Cargo do Executor','texto','Cargo do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},57,'Departamento do Executor','texto','Departamento do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},58,'Profissão do Executor','texto','Profissao do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},59,'Setor do Executor','texto','Setor do executor','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},60,'Pergunta da Condição','texto','Pergunta da condicao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},61,'Resposta Selecionada','texto','Resposta selecionada','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},62,'Pergunta da Regra','texto','Pergunta da regra','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},63,'Ordem da Regra','texto','Ordem da regra','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},64,'Resposta da Regra','texto','Resposta da regra','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},65,'Dt. Cadast. da Solicitação','data','DATA de cadastro da solicitacao',NULL,NULL,'%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},66,'Ano Cadast. da Solicitação','valor','Ano do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},67,'Mês Cadast. da Solicitação','valor','Mes do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},68,'Dia Cadast. da Solicitação','valor','Dia do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},69,'Dia da Semana Cadast. da Solicitação','texto','Nome do dia do cadastro da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},70,'Semana Cadast. da Solicitação','valor','Semana do ano do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},71,'Semana Abert. da Solicitação','valor','Dia da semana da abertura da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},72,'Dt. Concl. da Solicitação','data','DATA de conclusao da solicitacao','','','%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},73,'Ano Concl. da Solicitação','valor','Ano do conclusao da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},74,'Mês Concl. da Solicitação','valor','Mes do conclusao da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},75,'Dia Concl. da Solicitação','valor','Dia do conclusao da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},76,'Dia da Semana Concl. da Solicitação','texto','Nome do dia da conclusao da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},77,'Semana Concl. da Solicitação (Ano)','valor','Semana do ano conclusao da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},78,'Semana Concl. da Solicitação (Semana)','valor','Dia da semana da conclusao da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},79,'Dt. Cadast. da Atividade','data','DATA de cadastro da atividade',NULL,NULL,'%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},80,'Ano Cadast. da Atividade','valor','Ano do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},81,'Mês Cadast. da Atividade','valor','Mes do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},82,'Dia Cadast. da Atividade','valor','Dia do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},83,'Dia da Semana Cadast. da Atividade','texto','Nome do dia do cadastro da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},84,'Semana Cadast. da Atividade','valor','Semana do ano do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},85,'Semana Abert. da Atividade	','valor','Dia da semana da abertura da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},86,'Dt. Concl. da Atividade	','data','DATA de conclusao da atividade',NULL,NULL,'%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},87,'Ano Concl. da Atividade	','valor','Ano do conclusao da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},88,'Mês Concl. da Atividade	','valor','Mes do conclusao da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},89,'Dia Concl. da Atividade	','valor','Dia do conclusao da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},90,'Dia da Semana Concl. da Atividade	','texto','Nome do dia da conclusao da atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},91,'Semana Concl. da Atividade (Ano)','valor','Semana do ano conclusao da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},92,'Semana Concl. da Atividade (Semana)','valor','Dia da semana da conclusao da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},93,'Quantidade','valor','Quantidade',NULL,NULL,NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},94,'Identificador da Atividade','valor','identificadorAtividade',NULL,NULL,NULL,0);
   
SQL;

        Yii::$app->db->createCommand($sql_campos)->execute();

        $sql_table = <<<SQL
    
            CREATE TABLE `bpbi_indicador{$id_indicador}` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `id_indicador` bigint(20) DEFAULT NULL,
                `valor0` text,
                `valor1` text,
                `valor2` text,
                `valor3` text,
                `valor4` text,
                `valor5` text,
                `valor6` text,
                `valor7` text,
                `valor8` text,
                `valor9` text,
                `valor10` text,
                `valor11` text,
                `valor12` text,
                `valor13` text,
                `valor14` text,
                `valor15` text,
                `valor16` text,
                `valor17` text,
                `valor18` text,
                `valor19` text,
                `valor20` text,
                `valor21` text,
                `valor22` text,
                `valor23` text,
                `valor24` text,
                `valor25` text,
                `valor26` text,
                `valor27` text,
                `valor28` text,
                `valor29` text,
                `valor30` text,
                `valor31` text,
                `valor32` text,
                `valor33` text,
                `valor34` text,
                `valor35` text,
                `valor36` text,
                `valor37` text,
                `valor38` text,
                `valor39` text,
                `valor40` text,
                `valor41` text,
                `valor42` text,
                `valor43` text,
                `valor44` text,
                `valor45` text,
                `valor46` text,
                `valor47` text,
                `valor48` text,
                `valor49` text,
                `valor50` text,
                `valor51` text,
                `valor52` text,
                `valor53` text,
                `valor54` text,
                `valor55` text,
                `valor56` text,
                `valor57` text,
                `valor58` text,
                `valor59` text,
                `valor60` text,
                `valor61` text,
                `valor62` text,
                `valor63` text,
                `valor64` text,
                `valor65` text,
                `valor66` text,
                `valor67` text,
                `valor68` text,
                `valor69` text,
                `valor70` text,
                `valor71` text,
                `valor72` text,
                `valor73` text,
                `valor74` text,
                `valor75` text,
                `valor76` text,
                `valor77` text,
                `valor78` text,
                `valor79` text,
                `valor80` text,
                `valor81` text,
                `valor82` text,
                `valor83` text,
                `valor84` text,
                `valor85` text,
                `valor86` text,
                `valor87` text,
                `valor88` text,
                `valor89` text,
                `valor90` text,
                `valor91` text,
                `valor92` varchar(45) DEFAULT NULL,
                `valor93` varchar(45) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

        Yii::$app->db->createCommand($sql_table)->execute();
    }

    public function actionFormulario($id_conexao) {
        parent::stdout("Configurando o Cubo Padrão do Bee - Formulários\n\n", Console::BG_BLUE);

        $sql_indicador = <<<SQL
    
            INSERT INTO `bpbi_indicador` (`id_conexao`,`tipo`,`nome`,`descricao`,`sql`,`periodicidade`) VALUES ({$id_conexao},'database','Bee Padrão - Formulários','Bee Padrão - Formulários','SELECT DATE_FORMAT(envio.dataEnvio, \'%d/%m/%Y\') AS `DATA de cadastro do formulario`, YEAR(envio.dataEnvio) AS `Ano de cadastro do formulario`, MONTH(envio.dataEnvio) AS `Mês de cadastro do formulario`, DAY(envio.dataEnvio) AS `Dia de cadastro do formulario`, DATE_FORMAT(prazoSol.dataCadastro, \'%d/%m/%Y\') AS `DATA do cadastro da solicitacao`, YEAR(prazoSol.dataCadastro) AS `Ano do cadastro da solicitacao`, MONTH(prazoSol.dataCadastro) AS `Mês do cadastro da solicitacao`, DAY(prazoSol.dataCadastro) AS `Dia do cadastro da solicitacao`, DATE_FORMAT(prazoSol.dataConclusao, \'%d/%m/%Y\') AS `DATA do conclusão da solicitacao`, YEAR(prazoSol.dataConclusao) AS `Ano do conclusão da solicitacao`, MONTH(prazoSol.dataConclusao) AS `Mês do conclusão da solicitacao`, DAY(prazoSol.dataConclusao) AS `Dia do conclusão da solicitacao`, DATEDIFF(COALESCE(prazoSol.dataConclusao, NOW()), prazoSol.dataCadastro) AS `Dias usado na solicitacao`, DATE_FORMAT(prazoAti.dataCadastro, \'%d/%m/%Y\') AS `DATA do cadastro da atividade`, YEAR(prazoAti.dataCadastro) AS `Ano do cadastro da atividade`, MONTH(prazoAti.dataCadastro) AS `Mês do cadastro da atividade`, DAY(prazoAti.dataCadastro) AS `Dia do cadastro da atividade`, DATE_FORMAT(prazoAti.dataConclusao, \'%d/%m/%Y\') AS `DATA do conclusão da atividade`, YEAR(prazoAti.dataConclusao) AS `Ano do conclusão da atividade`, MONTH(prazoAti.dataConclusao) AS `Mês do conclusão da atividade`, DAY(prazoAti.dataConclusao) AS `Dia do conclusão da atividade`, DATEDIFF(COALESCE(prazoAti.dataConclusao, NOW()), prazoAti.dataCadastro) AS `Dias usados na atividade`, formulario.id AS `Identificador do Formulario`, formulario.nome AS `Formulario`, solicitacao.id AS `Identificador da Solicitacao`, solicitacao.nomeResumoProcesso AS `Nome do processo`, solicitacao.codigo AS `Codigo da Solicitacao`, solicitacao.caminhoCategoria AS `Categoria da Solicitacao`, solicitacao.status AS `Status da Solicitacao`, solicitacao.condicao AS `Condicao de prazo da Solicitacao`, solicitacao.marco AS `Marco da Solicitacao`, solicitacao.prioridade AS `Prioridade da Solicitacao`, solicitacao.finalizacaoSucesso AS `Solicitacao finalizada com sucesso`, solicitacao.tempo AS `Tempo da Solicitacao`, atividade.id AS `Identificador da Atividade`, atividade.nome AS `Nome da Atividade`, atividade.status AS `Status da Atividade`, atividade.tempo AS `Tempo da Atividade`, atividade.condicao AS `Condicao de prazo da Atividade`, objetoUsuario.identificador AS `Identificador do objeto`, objetoUsuario.nome AS `Nome do objeto`, objeto.empresa AS `Empresa do objeto`, objetoPessoal.cidade AS `Cidade do objeto`, objetoPessoal.estado AS `Estado do objeto`, objetoUsuario.rota AS `Rota do objeto`, objetoUsuario.cargo AS `Cargo do objeto`, objetoUsuario.departamento AS `Departamento do objeto`, objetoUsuario.profissao AS `Profissao do objeto`, objetoPessoal.setor AS `Setor do objeto`, designadoUsuario.nome AS `Nome do designado`, designado.empresa AS `Empresa do designado`, designadoPessoal.cidade AS `Cidade do designado`, designadoPessoal.estado AS `Estado do designado`, designadoUsuario.rota AS `Rota do designado`, designadoUsuario.cargo AS `Cargo do designado`, designadoUsuario.departamento AS `Departamento do designado`, designadoUsuario.profissao AS `Profissão do designado`, designadoPessoal.setor AS `Setor do designado`, pergunta.nomeCampo AS `Pergunta`, pergunta.ordem AS `Ordem da Pergunta`, CASE pergunta.ordem WHEN 1 THEN ( SELECT valor1 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor1 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 2 THEN ( SELECT valor2 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor2 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 3 THEN ( SELECT valor3 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor3 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 4 THEN ( SELECT valor4 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor4 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 5 THEN ( SELECT valor5 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor5 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 6 THEN ( SELECT valor6 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor6 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 7 THEN ( SELECT valor7 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor7 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 8 THEN ( SELECT valor8 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor8 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 9 THEN ( SELECT valor9 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor9 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 10 THEN ( SELECT valor10 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor10 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 11 THEN ( SELECT valor11 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor11 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 12 THEN ( SELECT valor12 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor12 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 13 THEN ( SELECT valor13 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor13 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 14 THEN ( SELECT valor14 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor14 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 15 THEN ( SELECT valor15 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor15 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 16 THEN ( SELECT valor16 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor16 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 17 THEN ( SELECT valor17 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor17 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 18 THEN ( SELECT valor18 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor18 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 19 THEN ( SELECT valor19 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor19 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 20 THEN ( SELECT valor20 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor20 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 21 THEN ( SELECT valor21 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor21 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 22 THEN ( SELECT valor22 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor22 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 23 THEN ( SELECT valor23 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor23 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 24 THEN ( SELECT valor24 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor24 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 25 THEN ( SELECT valor25 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor25 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 26 THEN ( SELECT valor26 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor26 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 27 THEN ( SELECT valor27 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor27 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 28 THEN ( SELECT valor28 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor28 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 29 THEN ( SELECT valor29 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor29 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 30 THEN ( SELECT valor30 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor30 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 31 THEN ( SELECT valor31 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor31 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 32 THEN ( SELECT valor32 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor32 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 33 THEN ( SELECT valor33 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor33 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 34 THEN ( SELECT valor34 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor34 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 35 THEN ( SELECT valor35 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor35 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 36 THEN ( SELECT valor36 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor36 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 37 THEN ( SELECT valor37 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor37 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 38 THEN ( SELECT valor38 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor38 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 39 THEN ( SELECT valor39 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor39 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 40 THEN ( SELECT valor40 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor40 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 41 THEN ( SELECT valor41 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor41 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 42 THEN ( SELECT valor42 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor42 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 43 THEN ( SELECT valor43 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor43 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 44 THEN ( SELECT valor44 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor44 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 45 THEN ( SELECT valor45 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor45 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 46 THEN ( SELECT valor46 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor46 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 47 THEN ( SELECT valor47 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor47 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 48 THEN ( SELECT valor48 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor48 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 49 THEN ( SELECT valor49 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor49 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 50 THEN ( SELECT valor50 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor50 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 51 THEN ( SELECT valor51 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor51 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 52 THEN ( SELECT valor52 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor52 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 53 THEN ( SELECT valor53 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor53 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 54 THEN ( SELECT valor54 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor54 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 55 THEN ( SELECT valor55 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor55 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 56 THEN ( SELECT valor56 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor56 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 57 THEN ( SELECT valor57 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor57 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 58 THEN ( SELECT valor58 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor58 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 59 THEN ( SELECT valor59 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor59 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 60 THEN ( SELECT valor60 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor60 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 61 THEN ( SELECT valor61 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor61 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 62 THEN ( SELECT valor62 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor62 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 63 THEN ( SELECT valor63 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor63 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 64 THEN ( SELECT valor64 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor64 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 65 THEN ( SELECT valor65 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor65 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 66 THEN ( SELECT valor66 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor66 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 67 THEN ( SELECT valor67 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor67 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 68 THEN ( SELECT valor68 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor68 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 69 THEN ( SELECT valor69 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor69 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 70 THEN ( SELECT valor70 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor70 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 71 THEN ( SELECT valor71 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor71 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 72 THEN ( SELECT valor72 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor72 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 73 THEN ( SELECT valor73 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor73 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 74 THEN ( SELECT valor74 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor74 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 75 THEN ( SELECT valor75 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor75 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 76 THEN ( SELECT valor76 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor76 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 77 THEN ( SELECT valor77 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor77 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 78 THEN ( SELECT valor78 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor78 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 79 THEN ( SELECT valor79 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor79 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 80 THEN ( SELECT valor80 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor80 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 81 THEN ( SELECT valor81 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor81 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 82 THEN ( SELECT valor82 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor82 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 83 THEN ( SELECT valor83 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor83 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 84 THEN ( SELECT valor84 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor84 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 85 THEN ( SELECT valor85 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor85 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 86 THEN ( SELECT valor86 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor86 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 87 THEN ( SELECT valor87 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor87 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 88 THEN ( SELECT valor88 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor88 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 89 THEN ( SELECT valor89 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor89 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 90 THEN ( SELECT valor90 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor90 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 91 THEN ( SELECT valor91 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor91 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 92 THEN ( SELECT valor92 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor92 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 93 THEN ( SELECT valor93 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor93 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 94 THEN ( SELECT valor94 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor94 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 95 THEN ( SELECT valor95 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor95 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 96 THEN ( SELECT valor96 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor96 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 97 THEN ( SELECT valor97 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor97 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 98 THEN ( SELECT valor98 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor98 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 99 THEN ( SELECT valor99 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor99 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) WHEN 100 THEN ( SELECT valor100 FROM pr_solicitacao_atividade_formulario_envio envio1 WHERE envio1.valor100 IS NOT NULL AND envio1.formulario_id = pergunta.formulario_id AND envio1.solicitacao_id = solicitacao.id AND envio1.atividade_id = atividade.id AND envio1.id = envio.id) ELSE \'\' END AS `Resposta`, 1 AS `Quantidade` FROM formulario INNER JOIN formularioitem pergunta ON pergunta.formulario_id = formulario.id INNER JOIN pr_solicitacao_atividade_formulario_envio envio ON envio.formulario_id = formulario.id INNER JOIN pr_solicitacao solicitacao ON solicitacao.id = envio.solicitacao_id INNER JOIN pr_solicitacao_atividade atividade ON atividade.id = envio.atividade_id INNER JOIN pr_solicitacao_entidade objeto ON objeto.processo_id = solicitacao.id AND objeto.tipoObjeto = \'Objeto\' INNER JOIN pr_solicitacao_prazo prazoSol ON prazoSol.id = solicitacao.prazo_id INNER JOIN pr_solicitacao_prazo prazoAti ON prazoAti.id = atividade.prazo_id LEFT JOIN admin_usuario objetoUsuario ON objetoUsuario.id = objeto.obj_id LEFT JOIN admin_usuario_pessoal objetoPessoal ON objetoPessoal.id = objetoUsuario.id LEFT JOIN pr_solicitacao_entidade designado ON designado.processo_id = solicitacao.id AND designado.tipoObjeto = \'Designado\' LEFT JOIN admin_usuario designadoUsuario ON designadoUsuario.id = designado.obj_id LEFT JOIN admin_usuario_pessoal designadoPessoal ON designadoPessoal.id = designadoUsuario.id LEFT JOIN pr_solicitacao_arquivo arquivo ON arquivo.solicitacao_id = solicitacao.id','86400'); 
SQL;

        Yii::$app->db->createCommand($sql_indicador)->execute();

        $id_indicador = Yii::$app->db->createCommand("SELECT id FROM bpbi_indicador WHERE nome = 'Bee Padrão - Formulários'")->queryScalar();

        parent::stdout("Código do indicador: {$id_indicador}\n\n", Console::BG_GREEN);

        $sql_campos = <<<SQL
    
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},1,'Dt. Cadast. do Formulário','data','DATA de cadastro do formulario','','','%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},2,'Ano Cadast. do Formulário','valor','Ano de cadastro do formulario','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},3,'Mês Cadast. do Formulário','valor','Mês de cadastro do formulario','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},4,'Dia Cadast. do Formulário','valor','Dia de cadastro do formulario','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},5,'Dt. Cadast. da Solicitação','data','DATA do cadastro da solicitacao','','','%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},6,'Ano Cadast. da Solicitação','valor','Ano do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},7,'Mês Cadast. da Solicitação','valor','Mês do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},8,'Dia Cadast. da Solicitação','valor','Dia do cadastro da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},9,'Dt. Concl. da Solicitação','data','DATA do conclusão da solicitacao','','','%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},10,'Ano Concl. da Solicitação','valor','Ano do conclusão da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},11,'Mês Concl. da Solicitação','valor','Mês do conclusão da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},12,'Dia Concl. da Solicitação','valor','Dia do conclusão da solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},13,'Dias Usados na Solicitação','valor','Dias usado na solicitacao','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},14,'Dia Cadast. da Atividade','data','DATA do cadastro da atividade','','','%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},15,'Ano Cadast. da Atividade','valor','Ano do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},16,'Mês Cadast. da Atividade','valor','Mês do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},17,'Dia Cadast. da Atividade','valor','Dia do cadastro da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},18,'Dt. Concl. da Atividade','data','DATA do conclusão da atividade','','','%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},19,'Ano Concl. da Atividade	','valor','Ano do conclusão da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},20,'Mês Concl. da Atividade	','valor','Mês do conclusão da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},21,'Dia Concl. da Atividade','valor','Dia do conclusão da atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},22,'Dias Usados na Atividade','valor','Dias usados na atividade','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},23,'Identificador do Formulário','texto','Identificador do Formulario','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},24,'Formulário','texto','Formulario','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},25,'Identificador da Solicitação','texto','Identificador da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},26,'Nome do Processo','texto','Nome do processo',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},27,'Código da Solicitação','texto','Codigo da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},28,'Categoria da Solicitação','texto','Categoria da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},29,'Status da Solicitação','texto','Status da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},30,'Condição de Prazo da Solicitação','texto','Condicao de prazo da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},31,'Marco da Solicitação','texto','Marco da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},32,'Prioridade da Solicitação','texto','Prioridade da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},33,'Solicitação Finalizada com Sucesso','texto','Solicitacao finalizada com sucesso','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},34,'Tempo da Solicitação','texto','Tempo da Solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},35,'Identificador da Atividade','texto','Identificador da Atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},36,'Nome da Atividade','texto','Nome da Atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},37,'Status da Atividade','texto','Status da Atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},38,'Tempo da Atividade','texto','Tempo da Atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},39,'Condição de Prazo da Atividade','texto','Condicao de prazo da Atividade','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},40,'Identificador do Objeto','texto','Identificador do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},41,'Nome do Objeto','texto','Nome do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},42,'Empresa do Objeto','texto','Empresa do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},43,'Cidade do Objeto','texto','Cidade do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},44,'Estado do Objeto','texto','Estado do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},45,'Rota do Objeto','texto','Rota do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},46,'Cargo do Objeto','texto','Cargo do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},47,'Departamento do Objeto','texto','Departamento do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},48,'Profissão do Objeto','texto','Profissao do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},49,'Setor do Objeto','texto','Setor do objeto','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},50,'Nome do Designado','texto','Nome do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},51,'Empresa do Designado','texto','Empresa do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},52,'Cidade do Designado','texto','Cidade do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},53,'Estado do Designado','texto','Estado do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},54,'Rota do Designado','texto','Rota do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},55,'Cargo do Designado','texto','Cargo do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},56,'Departamento do Designado','texto','Departamento do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},57,'Profissão do Designado','texto','Profissão do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},58,'Setor do Designado','texto','Setor do designado','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},59,'Pergunta','texto','Pergunta','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},60,'Ordem da Pergunta','texto','Ordem da Pergunta','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},61,'Resposta','texto','Resposta','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},62,'Quantidade','valor','Quantidade','','',NULL,0);
                
SQL;

        Yii::$app->db->createCommand($sql_campos)->execute();

        $sql_table = <<<SQL
    
            CREATE TABLE `bpbi_indicador{$id_indicador}` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `id_indicador` bigint(20) DEFAULT NULL,
                `valor0` varchar(255) DEFAULT NULL,
                `valor1` varchar(255) DEFAULT NULL,
                `valor2` varchar(255) DEFAULT NULL,
                `valor3` varchar(255) DEFAULT NULL,
                `valor4` varchar(255) DEFAULT NULL,
                `valor5` varchar(255) DEFAULT NULL,
                `valor6` varchar(255) DEFAULT NULL,
                `valor7` varchar(255) DEFAULT NULL,
                `valor8` varchar(255) DEFAULT NULL,
                `valor9` varchar(255) DEFAULT NULL,
                `valor10` varchar(255) DEFAULT NULL,
                `valor11` varchar(255) DEFAULT NULL,
                `valor12` varchar(255) DEFAULT NULL,
                `valor13` varchar(255) DEFAULT NULL,
                `valor14` varchar(255) DEFAULT NULL,
                `valor15` varchar(255) DEFAULT NULL,
                `valor16` varchar(255) DEFAULT NULL,
                `valor17` varchar(255) DEFAULT NULL,
                `valor18` varchar(255) DEFAULT NULL,
                `valor19` varchar(255) DEFAULT NULL,
                `valor20` varchar(255) DEFAULT NULL,
                `valor21` varchar(255) DEFAULT NULL,
                `valor22` varchar(255) DEFAULT NULL,
                `valor23` varchar(255) DEFAULT NULL,
                `valor24` varchar(255) DEFAULT NULL,
                `valor25` varchar(255) DEFAULT NULL,
                `valor26` varchar(255) DEFAULT NULL,
                `valor27` varchar(255) DEFAULT NULL,
                `valor28` varchar(255) DEFAULT NULL,
                `valor29` varchar(255) DEFAULT NULL,
                `valor30` varchar(255) DEFAULT NULL,
                `valor31` varchar(255) DEFAULT NULL,
                `valor32` varchar(255) DEFAULT NULL,
                `valor33` varchar(255) DEFAULT NULL,
                `valor34` varchar(255) DEFAULT NULL,
                `valor35` varchar(255) DEFAULT NULL,
                `valor36` varchar(255) DEFAULT NULL,
                `valor37` varchar(255) DEFAULT NULL,
                `valor38` varchar(255) DEFAULT NULL,
                `valor39` varchar(255) DEFAULT NULL,
                `valor40` varchar(255) DEFAULT NULL,
                `valor41` varchar(255) DEFAULT NULL,
                `valor42` varchar(255) DEFAULT NULL,
                `valor43` varchar(255) DEFAULT NULL,
                `valor44` varchar(255) DEFAULT NULL,
                `valor45` varchar(255) DEFAULT NULL,
                `valor46` varchar(255) DEFAULT NULL,
                `valor47` varchar(255) DEFAULT NULL,
                `valor48` varchar(255) DEFAULT NULL,
                `valor49` varchar(255) DEFAULT NULL,
                `valor50` varchar(255) DEFAULT NULL,
                `valor51` varchar(255) DEFAULT NULL,
                `valor52` varchar(255) DEFAULT NULL,
                `valor53` varchar(255) DEFAULT NULL,
                `valor54` varchar(255) DEFAULT NULL,
                `valor55` varchar(255) DEFAULT NULL,
                `valor56` varchar(255) DEFAULT NULL,
                `valor57` varchar(255) DEFAULT NULL,
                `valor58` varchar(255) DEFAULT NULL,
                `valor59` varchar(255) DEFAULT NULL,
                `valor60` text,
                `valor61` varchar(255) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `bpbi_indicador67_0` (`valor0`),
                KEY `bpbi_indicador67_1` (`valor1`),
                KEY `bpbi_indicador67_2` (`valor2`),
                KEY `bpbi_indicador67_3` (`valor3`),
                KEY `bpbi_indicador67_4` (`valor4`),
                KEY `bpbi_indicador67_5` (`valor5`),
                KEY `bpbi_indicador67_6` (`valor6`),
                KEY `bpbi_indicador67_7` (`valor7`),
                KEY `bpbi_indicador67_8` (`valor8`),
                KEY `bpbi_indicador67_9` (`valor9`),
                KEY `bpbi_indicador67_10` (`valor10`),
                KEY `bpbi_indicador67_11` (`valor11`),
                KEY `bpbi_indicador67_12` (`valor12`),
                KEY `bpbi_indicador67_13` (`valor13`),
                KEY `bpbi_indicador67_14` (`valor14`),
                KEY `bpbi_indicador67_15` (`valor15`),
                KEY `bpbi_indicador67_16` (`valor16`),
                KEY `bpbi_indicador67_17` (`valor17`),
                KEY `bpbi_indicador67_18` (`valor18`),
                KEY `bpbi_indicador67_19` (`valor19`),
                KEY `bpbi_indicador67_20` (`valor20`),
                KEY `bpbi_indicador67_21` (`valor21`),
                KEY `bpbi_indicador67_22` (`valor22`),
                KEY `bpbi_indicador67_23` (`valor23`),
                KEY `bpbi_indicador67_24` (`valor24`),
                KEY `bpbi_indicador67_25` (`valor25`),
                KEY `bpbi_indicador67_26` (`valor26`),
                KEY `bpbi_indicador67_27` (`valor27`),
                KEY `bpbi_indicador67_28` (`valor28`),
                KEY `bpbi_indicador67_29` (`valor29`),
                KEY `bpbi_indicador67_30` (`valor30`),
                KEY `bpbi_indicador67_31` (`valor31`),
                KEY `bpbi_indicador67_32` (`valor32`),
                KEY `bpbi_indicador67_33` (`valor33`),
                KEY `bpbi_indicador67_34` (`valor34`),
                KEY `bpbi_indicador67_35` (`valor35`),
                KEY `bpbi_indicador67_36` (`valor36`),
                KEY `bpbi_indicador67_37` (`valor37`),
                KEY `bpbi_indicador67_38` (`valor38`),
                KEY `bpbi_indicador67_39` (`valor39`),
                KEY `bpbi_indicador67_40` (`valor40`),
                KEY `bpbi_indicador67_41` (`valor41`),
                KEY `bpbi_indicador67_42` (`valor42`),
                KEY `bpbi_indicador67_43` (`valor43`),
                KEY `bpbi_indicador67_44` (`valor44`),
                KEY `bpbi_indicador67_45` (`valor45`),
                KEY `bpbi_indicador67_46` (`valor46`),
                KEY `bpbi_indicador67_47` (`valor47`),
                KEY `bpbi_indicador67_48` (`valor48`),
                KEY `bpbi_indicador67_49` (`valor49`),
                KEY `bpbi_indicador67_50` (`valor50`),
                KEY `bpbi_indicador67_51` (`valor51`),
                KEY `bpbi_indicador67_52` (`valor52`),
                KEY `bpbi_indicador67_53` (`valor53`),
                KEY `bpbi_indicador67_54` (`valor54`),
                KEY `bpbi_indicador67_55` (`valor55`),
                KEY `bpbi_indicador67_56` (`valor56`),
                KEY `bpbi_indicador67_57` (`valor57`),
                KEY `bpbi_indicador67_58` (`valor58`),
                KEY `bpbi_indicador67_59` (`valor59`),
                KEY `bpbi_indicador67_61` (`valor61`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

        Yii::$app->db->createCommand($sql_table)->execute();
    }

    public function actionSolicitacao($id_conexao) {
        parent::stdout("Configurando o Cubo Padrão do Bee - Solicitações\n\n", Console::BG_BLUE);

        $sql_indicador = <<<SQL
    
            INSERT INTO `bpbi_indicador` (`id_conexao`,`tipo`,`nome`,`descricao`,`sql`,`periodicidade`) VALUES ({$id_conexao},'database','Bee Padrão - Solicitações','Bee Padrão - Solicitações','SELECT\n	DISTINCT solicitacao.codigo AS `Código`,\n	LTRIM(SUBSTRING_INDEX(solicitacao.caminhoCategoria, \'/\', -1)) AS `Categoria`,\n	fluxo.nome AS `Nome do Processo`,\n	fluxo.descricao AS `Descrição do Processo`,\n	solicitacao.id AS `ID da Solicitação`,\n	solicitacao.status AS `Status da Solicitação`,\n	CASE\n		WHEN prazo.dataConclusao <= prazo.dataPrevistaConclusao THEN \'Dentro do Prazo\'\n		WHEN (prazo.dataProrrogacao IS NOT NULL\n		AND prazo.dataProrrogacao > NOW()) THEN \'Dentro do Prazo\'\n		WHEN prazo.dataConclusao <= NOW() THEN \'Dentro do Prazo\'\n		ELSE \'Fora do Prazo\'\n	END AS `Status do prazo`,\n	solicitacao.prioridade AS `Prioridade da Solicitação`,\n	CASE\n		WHEN solicitacao.finalizacaoSucesso = \'Não\' THEN \'Não\'\n		WHEN solicitacao.finalizacaoSucesso IS NOT NULL THEN \'Não\'\n		ELSE \'Sim\'\n	END AS `Finalizado com Sucesso`,\n	CASE\n		WHEN solicitacao.status = \'Concluída\' THEN \'Concluída\'\n		ELSE solicitacao.tempo\n	END AS `Status de execução`,\n	custo.valorTotalGeral AS `Valor Total Geral`,\n	custo.valorTotalReceitaPrevista AS `Valor previsto de Receita`,\n	custo.valorTotalReceitaConsolidada AS `Valor consolidado de Receita`,\n	custo.valorTotalDespesaPrevista AS `Valor previsto de Despesa`,\n	custo.valorTotalDespesaConsolidada AS `Valor consolidado de Despesa`,\n	custo.valorHorasServico AS `Valor de Horas de Serviço`,\n	atividadeCompleta.id AS `ID da última Atividade`,\n	atividadeCompleta.nome AS `Nome da última Atividade`,\n	executor.nome AS `Nome do executor da última Atividade`,\n	atividadeCompleta.status AS `Status da atividade`,\n	DATEDIFF(COALESCE(prazo.dataConclusao,\n	NOW()),\n	prazo.dataCadastro) AS `Dias usados na Solicitação`,\n	atividadeCompleta.ultimoAndamento AS `Último andamento da atividade`,\n	atividadeCompleta.condicao AS `Status do prazo da atividade`,\n	usuarioObjeto.identificador AS `Identificador do objeto`,\n	usuarioObjeto.nome AS `Nome do objeto`,\n	empresaObjeto.nomeFantasia AS `Nome da empresa do objeto`,\n	pessoalObjeto.cidade AS `Cidade do objeto`,\n	pessoalObjeto.estado AS `Estado do objeto`,\n	usuarioObjeto.rota AS `Rota do objeto`,\n	usuarioObjeto.cargo AS `Cargo do objeto`,\n	usuarioObjeto.departamento AS `Departamento do objeto`,\n	usuarioObjeto.profissao AS `Profissão do objeto`,\n	pessoalObjeto.setor AS `Setor do objeto`,\n	usuarioDesignado.identificador AS `Identificador do designado`,\n	usuarioDesignado.nome AS `Nome do designado`,\n	empresaDesignado.nomeFantasia AS `Nome da empresa do designado`,\n	pessoalDesignado.cidade AS `Cidade do designado`,\n	pessoalDesignado.estado AS `Estado do designado`,\n	usuarioDesignado.rota AS `Rota do designado`,\n	usuarioDesignado.cargo AS `Cargo do designado`,\n	usuarioDesignado.departamento AS `Departamento do designado`,\n	usuarioDesignado.profissao AS `Profissão do designado`,\n	pessoalDesignado.setor AS `Setor do designado`,\n	usuarioCadastradoPor.identificador AS `Identificador do cadastradoPor`,\n	usuarioCadastradoPor.nome AS `Nome do cadastradoPor`,\n	empresaCadastradoPor.nomeFantasia AS `Nome da empresa do cadastradoPor`,\n	pessoalCadastradoPor.cidade AS `Cidade do cadastradoPor`,\n	pessoalCadastradoPor.estado AS `Estado do cadastradoPor`,\n	usuarioCadastradoPor.rota AS `Rota do cadastradoPor`,\n	usuarioCadastradoPor.cargo AS `Cargo do cadastradoPor`,\n	usuarioCadastradoPor.departamento AS `Departamento do cadastradoPor`,\n	usuarioCadastradoPor.profissao AS `Profissão do cadastradoPor`,\n	pessoalCadastradoPor.setor AS `Setor do cadastradoPor`,\n	prazo.dataCadastro AS `Data de cadastro da Solicitação`,\n	YEAR(prazo.dataCadastro) AS `Ano do cadastro da Solicitação`,\n	MONTH(prazo.dataCadastro) AS `Mês do cadastro da Solicitação`,\n	DAY(prazo.dataCadastro) AS `Dia do cadastro da Solicitação`,\n	CASE\n		WHEN DAYNAME(prazo.dataCadastro) = \'Monday\' THEN \'Segunda-Feira\'\n		WHEN DAYNAME(prazo.dataCadastro) = \'Tuesday\' THEN \'Terça-Feira\'\n		WHEN DAYNAME(prazo.dataCadastro) = \'Wednesday\' THEN \'Quarta-Feira\'\n		WHEN DAYNAME(prazo.dataCadastro) = \'Thursday\' THEN \'Quinta-Feira\'\n		WHEN DAYNAME(prazo.dataCadastro) = \'Friday\' THEN \'Sexta-Feira\'\n		WHEN DAYNAME(prazo.dataCadastro) = \'Saturday\' THEN \'Sábado\'\n		WHEN DAYNAME(prazo.dataCadastro) = \'Sunday\' THEN \'Domingo\'\n	END AS `Nome do dia do cadastro da solicitacao`,\n	DAYOFMONTH(prazo.dataCadastro) AS `Semana do ano do cadastro da solicitacao`,\n	(WEEK(DATE_FORMAT(prazo.dataCadastro, \'%Y/%m/%d\')) - WEEK(DATE_FORMAT(prazo.dataCadastro, \'%Y/%m/%01\'))) + 1 AS `Dia da semana da abertura da Solicitação`,\n	prazo.dataConclusao AS `Data de conclusão da Solicitação`,\n	YEAR(prazo.dataConclusao) AS `Ano do conclusão da Solicitação`,\n	MONTH(prazo.dataConclusao) AS `Mês do conclusão da Solicitação`,\n	DAY(prazo.dataConclusao) AS `Dia do conclusão da Solicitação`,\n	CASE\n		WHEN DAYNAME(prazo.dataConclusao) = \'Monday\' THEN \'Segunda-Feira\'\n		WHEN DAYNAME(prazo.dataConclusao) = \'Tuesday\' THEN \'Terça-Feira\'\n		WHEN DAYNAME(prazo.dataConclusao) = \'Wednesday\' THEN \'Quarta-Feira\'\n		WHEN DAYNAME(prazo.dataConclusao) = \'Thursday\' THEN \'Quinta-Feira\'\n		WHEN DAYNAME(prazo.dataConclusao) = \'Friday\' THEN \'Sexta-Feira\'\n		WHEN DAYNAME(prazo.dataConclusao) = \'Saturday\' THEN \'Sábado\'\n		WHEN DAYNAME(prazo.dataConclusao) = \'Sunday\' THEN \'Domingo\'\n	END AS `Nome do dia da conclusão da Solicitação`,\n	DAYOFMONTH(prazo.dataConclusao) AS `Semana do ano conclusão da Solicitação`,\n	(WEEK(DATE_FORMAT(prazo.dataConclusao, \'%Y/%m/%d\')) - WEEK(DATE_FORMAT(prazo.dataConclusao, \'%Y/%m/%01\'))) + 1 AS `Dia da semana da conclusão da Solicitação`,\n    1 as Quantidade\nFROM\n	pr_solicitacao solicitacao\nINNER JOIN pr_fluxo fluxo ON\n	fluxo.id = solicitacao.processo_id\nINNER JOIN pr_solicitacao_prazo prazo ON\n	prazo.id = solicitacao.prazo_id\nLEFT JOIN pr_solicitacao_custo custo ON\n	custo.processo_id = solicitacao.id\nINNER JOIN (\n	SELECT\n		MAX(atividade.id) AS id,\n		atividade.processo_id,\n		MAX(atividade.entidade_id) AS entidade_id\n	FROM\n		pr_solicitacao_atividade atividade\n	INNER JOIN pr_solicitacao solicitacao\n	WHERE\n		atividade.status != \'Cancelada\'\n		AND atividade.processo_id = solicitacao.id\n	GROUP BY\n		atividade.processo_id) AS atividade ON\n	atividade.processo_id = solicitacao.id\nINNER JOIN pr_solicitacao_entidade executor ON\n	executor.id = atividade.entidade_id\nINNER JOIN pr_solicitacao_atividade atividadeCompleta ON\n	atividadeCompleta.id = atividade.id\nLEFT JOIN pr_solicitacao_regra regra ON\n	regra.tarefa_id = atividadeCompleta.id\nINNER JOIN pr_solicitacao_entidade objeto ON\n	objeto.processo_id = solicitacao.id\n	AND objeto.tipoObjeto = \'Objeto\'\nLEFT JOIN admin_usuario usuarioObjeto ON\n	usuarioObjeto.id = objeto.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalObjeto ON\n	pessoalObjeto.id = usuarioObjeto.id\nLEFT JOIN admin_empresa empresaObjeto ON\n	empresaObjeto.id = usuarioObjeto.empresa_id\nLEFT JOIN pr_solicitacao_entidade designado ON\n	designado.processo_id = solicitacao.id\n	AND designado.tipoObjeto = \'Designado\'\nLEFT JOIN admin_usuario usuarioDesignado ON\n	usuarioDesignado.id = designado.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalDesignado ON\n	pessoalDesignado.id = usuarioDesignado.id\nLEFT JOIN admin_empresa empresaDesignado ON\n	empresaDesignado.id = usuarioDesignado.empresa_id\nINNER JOIN pr_solicitacao_entidade cadastradoPor ON\n	cadastradoPor.processo_id = solicitacao.id\n	AND cadastradoPor.tipoObjeto = \'CadastradoPor\'\nLEFT JOIN admin_usuario usuarioCadastradoPor ON\n	usuarioCadastradoPor.id = cadastradoPor.obj_id\nLEFT JOIN admin_usuario_pessoal pessoalCadastradoPor ON\n	pessoalCadastradoPor.id = usuarioCadastradoPor.id\nLEFT JOIN admin_empresa empresaCadastradoPor ON\n	empresaCadastradoPor.id = usuarioCadastradoPor.empresa_id\n','86400');
            
SQL;

        Yii::$app->db->createCommand($sql_indicador)->execute();

        $id_indicador = Yii::$app->db->createCommand("SELECT id FROM bpbi_indicador WHERE nome = 'Bee Padrão - Solicitações'")->queryScalar();

        parent::stdout("Código do indicador: {$id_indicador}\n\n", Console::BG_GREEN);

        $sql_campos = <<<SQL
    
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},1,'Código','texto','Código',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},2,'Categoria','texto','Categoria',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},3,'Nome do Processo','texto','Nome do Processo',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},4,'Descrição do Processo','texto','Descrição do Processo',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},5,'Identificador da Solicitação','texto','ID da Solicitação','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},6,'Status da Solicitação','texto','Status da Solicitação',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},7,'Status do Prazo','texto','Status do prazo',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},8,'Prioridade da Solicitação','texto','Prioridade da Solicitação',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},9,'Finalizado com Sucesso','texto','Finalizado com Sucesso',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},10,'Status de Execução','texto','Status de execução',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},11,'Valor Total Geral','valor','Valor Total Geral','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},12,'Valor Previsto de Receita','valor','Valor previsto de Receita','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},13,'Valor Consolidado de Receita','valor','Valor consolidado de Receita','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},14,'Valor Previsto de Despesa','valor','Valor previsto de Despesa','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},15,'Valor Consolidado de Despesa','valor','Valor consolidado de Despesa','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},16,'Valor de Horas de Serviço','valor','Valor de Horas de Serviço','R$','',NULL,2);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},17,'Identificador da Últ. Atividade','texto','ID da última Atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},18,'Nome da Últ. Atividade','texto','Nome da última Atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},19,'Nome do Executor da Últ. Atividade','texto','Nome do executor da última Atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},20,'Status da Atividade','texto','Status da atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},21,'Dias Usados na Solicitação','valor','Dias usados na Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},22,'Últ. Andamento da Atividade','texto','Último andamento da atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},23,'Status do Prazo da Atividade','texto','Status do prazo da atividade',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},24,'Identificador do Objeto','texto','Identificador do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},25,'Nome do Objeto','texto','Nome do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},26,'Empresa do Objeto','texto','Nome da empresa do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},27,'Cidade do Objeto','texto','Cidade do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},28,'Estado do Objeto','texto','Estado do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},29,'Rota do Objeto','texto','Rota do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},30,'Cargo do Objeto','texto','Cargo do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},31,'Departamento do Objeto','texto','Departamento do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},32,'Profissão do Objeto','texto','Profissão do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},33,'Setor do Objeto','texto','Setor do objeto',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},34,'Identificador do Designado','texto','Identificador do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},35,'Nome do Designado','texto','Nome do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},36,'Empŕesa do Designado','texto','Nome da empresa do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},37,'Cidade do Designado','texto','Cidade do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},38,'Estado do Designado','texto','Estado do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},39,'Rota do Designado','texto','Rota do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},40,'Cargo do Designado','texto','Cargo do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},41,'Departamento do Designado','texto','Departamento do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},42,'Profissão do Designado','texto','Profissão do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},43,'Setor do Designado','texto','Setor do designado',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},44,'Identificador do Cadastrante','texto','Identificador do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},45,'Nome do Cadastrante','texto','Nome do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},46,'Empresa do Cadastrante','texto','Nome da empresa do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},47,'Cidade do Cadastrante','texto','Cidade do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},48,'Estado do Cadastrante','texto','Estado do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},49,'Rota do Cadastrante','texto','Rota do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},50,'Cargo do Cadastrante','texto','Cargo do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},51,'Departamento do Cadastrante','texto','Departamento do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},52,'Profissão do Cadastrante','texto','Profissão do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},53,'Setor do Cadastrante','texto','Setor do cadastradoPor',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},54,'Dia Cadast. da Solicitação','data','Data de cadastro da Solicitação',NULL,NULL,'%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},55,'Ano Cadast. da Solicitação','valor','Ano do cadastro da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},56,'Mês Cadast. da Solicitação','valor','Mês do cadastro da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},57,'Dia Cadast. da Solicitação','valor','Dia do cadastro da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},58,'Nome do Dia Cadast. da Solicitação','texto','Nome do dia do cadastro da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},59,'Semana Cadast. da Solicitação (Ano)','texto','Semana do ano do cadastro da solicitacao','','',NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},60,'Dia da Semana Cadast. da Solicitação','valor','Dia da semana da abertura da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},61,'Dt. Concl. da Solicitação','data','Data de conclusão da Solicitação',NULL,NULL,'%d/%m/%Y',NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},62,'Ano Concl. da Solicitação','valor','Ano do conclusão da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},63,'Mês Concl. da Solicitação','valor','Mês do conclusão da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},64,'Dia Concl. da Solicitação','valor','Dia do conclusão da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},65,'Nome do Dia Concl. da Solicitação','texto','Nome do dia da conclusão da Solicitação',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},66,'Semana Concl. da Solicitação (Ano)','texto','Semana do ano conclusão da Solicitação',NULL,NULL,NULL,NULL);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},67,'Dia da Semana Concl. da Solicitação','valor','Dia da semana da conclusão da Solicitação','','',NULL,0);
            INSERT INTO `bpbi_indicador_campo` (`id_indicador`,`ordem`,`nome`,`tipo`,`campo`,`prefixo`,`sufixo`,`formato`,`casas_decimais`) VALUES ({$id_indicador},68,'Quantidade','valor','Quantidade',NULL,NULL,NULL,0);
SQL;

        Yii::$app->db->createCommand($sql_campos)->execute();

        $sql_table = <<<SQL
    
            CREATE TABLE `bpbi_indicador{$id_indicador}` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `id_indicador` bigint(20) DEFAULT NULL,
                `valor0` varchar(255) DEFAULT NULL,
                `valor1` varchar(255) DEFAULT NULL,
                `valor2` varchar(255) DEFAULT NULL,
                `valor3` varchar(255) DEFAULT NULL,
                `valor4` varchar(255) DEFAULT NULL,
                `valor5` varchar(255) DEFAULT NULL,
                `valor6` varchar(255) DEFAULT NULL,
                `valor7` varchar(255) DEFAULT NULL,
                `valor8` varchar(255) DEFAULT NULL,
                `valor9` varchar(255) DEFAULT NULL,
                `valor10` varchar(255) DEFAULT NULL,
                `valor11` varchar(255) DEFAULT NULL,
                `valor12` varchar(255) DEFAULT NULL,
                `valor13` varchar(255) DEFAULT NULL,
                `valor14` varchar(255) DEFAULT NULL,
                `valor15` varchar(255) DEFAULT NULL,
                `valor16` varchar(255) DEFAULT NULL,
                `valor17` varchar(255) DEFAULT NULL,
                `valor18` varchar(255) DEFAULT NULL,
                `valor19` varchar(255) DEFAULT NULL,
                `valor20` varchar(255) DEFAULT NULL,
                `valor21` varchar(255) DEFAULT NULL,
                `valor22` varchar(255) DEFAULT NULL,
                `valor23` varchar(255) DEFAULT NULL,
                `valor24` varchar(255) DEFAULT NULL,
                `valor25` varchar(255) DEFAULT NULL,
                `valor26` varchar(255) DEFAULT NULL,
                `valor27` varchar(255) DEFAULT NULL,
                `valor28` varchar(255) DEFAULT NULL,
                `valor29` varchar(255) DEFAULT NULL,
                `valor30` varchar(255) DEFAULT NULL,
                `valor31` varchar(255) DEFAULT NULL,
                `valor32` varchar(255) DEFAULT NULL,
                `valor33` varchar(255) DEFAULT NULL,
                `valor34` varchar(255) DEFAULT NULL,
                `valor35` varchar(255) DEFAULT NULL,
                `valor36` varchar(255) DEFAULT NULL,
                `valor37` varchar(255) DEFAULT NULL,
                `valor38` varchar(255) DEFAULT NULL,
                `valor39` varchar(255) DEFAULT NULL,
                `valor40` varchar(255) DEFAULT NULL,
                `valor41` varchar(255) DEFAULT NULL,
                `valor42` varchar(255) DEFAULT NULL,
                `valor43` varchar(255) DEFAULT NULL,
                `valor44` varchar(255) DEFAULT NULL,
                `valor45` varchar(255) DEFAULT NULL,
                `valor46` varchar(255) DEFAULT NULL,
                `valor47` varchar(255) DEFAULT NULL,
                `valor48` varchar(255) DEFAULT NULL,
                `valor49` varchar(255) DEFAULT NULL,
                `valor50` varchar(255) DEFAULT NULL,
                `valor51` varchar(255) DEFAULT NULL,
                `valor52` varchar(255) DEFAULT NULL,
                `valor53` varchar(255) DEFAULT NULL,
                `valor54` varchar(255) DEFAULT NULL,
                `valor55` varchar(255) DEFAULT NULL,
                `valor56` varchar(255) DEFAULT NULL,
                `valor57` varchar(255) DEFAULT NULL,
                `valor58` varchar(255) DEFAULT NULL,
                `valor59` varchar(255) DEFAULT NULL,
                `valor60` varchar(255) DEFAULT NULL,
                `valor61` varchar(255) DEFAULT NULL,
                `valor62` varchar(255) DEFAULT NULL,
                `valor63` varchar(255) DEFAULT NULL,
                `valor64` varchar(255) DEFAULT NULL,
                `valor65` varchar(255) DEFAULT NULL,
                `valor66` varchar(255) DEFAULT NULL,
                `valor67` varchar(255) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `bpbi_indicador65_0` (`valor0`),
                KEY `bpbi_indicador65_1` (`valor1`),
                KEY `bpbi_indicador65_2` (`valor2`),
                KEY `bpbi_indicador65_3` (`valor3`),
                KEY `bpbi_indicador65_4` (`valor4`),
                KEY `bpbi_indicador65_5` (`valor5`),
                KEY `bpbi_indicador65_6` (`valor6`),
                KEY `bpbi_indicador65_7` (`valor7`),
                KEY `bpbi_indicador65_8` (`valor8`),
                KEY `bpbi_indicador65_9` (`valor9`),
                KEY `bpbi_indicador65_10` (`valor10`),
                KEY `bpbi_indicador65_11` (`valor11`),
                KEY `bpbi_indicador65_12` (`valor12`),
                KEY `bpbi_indicador65_13` (`valor13`),
                KEY `bpbi_indicador65_14` (`valor14`),
                KEY `bpbi_indicador65_15` (`valor15`),
                KEY `bpbi_indicador65_16` (`valor16`),
                KEY `bpbi_indicador65_17` (`valor17`),
                KEY `bpbi_indicador65_18` (`valor18`),
                KEY `bpbi_indicador65_19` (`valor19`),
                KEY `bpbi_indicador65_20` (`valor20`),
                KEY `bpbi_indicador65_21` (`valor21`),
                KEY `bpbi_indicador65_22` (`valor22`),
                KEY `bpbi_indicador65_23` (`valor23`),
                KEY `bpbi_indicador65_24` (`valor24`),
                KEY `bpbi_indicador65_25` (`valor25`),
                KEY `bpbi_indicador65_26` (`valor26`),
                KEY `bpbi_indicador65_27` (`valor27`),
                KEY `bpbi_indicador65_28` (`valor28`),
                KEY `bpbi_indicador65_29` (`valor29`),
                KEY `bpbi_indicador65_30` (`valor30`),
                KEY `bpbi_indicador65_31` (`valor31`),
                KEY `bpbi_indicador65_32` (`valor32`),
                KEY `bpbi_indicador65_33` (`valor33`),
                KEY `bpbi_indicador65_34` (`valor34`),
                KEY `bpbi_indicador65_35` (`valor35`),
                KEY `bpbi_indicador65_36` (`valor36`),
                KEY `bpbi_indicador65_37` (`valor37`),
                KEY `bpbi_indicador65_38` (`valor38`),
                KEY `bpbi_indicador65_39` (`valor39`),
                KEY `bpbi_indicador65_40` (`valor40`),
                KEY `bpbi_indicador65_41` (`valor41`),
                KEY `bpbi_indicador65_42` (`valor42`),
                KEY `bpbi_indicador65_43` (`valor43`),
                KEY `bpbi_indicador65_44` (`valor44`),
                KEY `bpbi_indicador65_45` (`valor45`),
                KEY `bpbi_indicador65_46` (`valor46`),
                KEY `bpbi_indicador65_47` (`valor47`),
                KEY `bpbi_indicador65_48` (`valor48`),
                KEY `bpbi_indicador65_49` (`valor49`),
                KEY `bpbi_indicador65_50` (`valor50`),
                KEY `bpbi_indicador65_51` (`valor51`),
                KEY `bpbi_indicador65_52` (`valor52`),
                KEY `bpbi_indicador65_53` (`valor53`),
                KEY `bpbi_indicador65_54` (`valor54`),
                KEY `bpbi_indicador65_55` (`valor55`),
                KEY `bpbi_indicador65_56` (`valor56`),
                KEY `bpbi_indicador65_57` (`valor57`),
                KEY `bpbi_indicador65_58` (`valor58`),
                KEY `bpbi_indicador65_59` (`valor59`),
                KEY `bpbi_indicador65_60` (`valor60`),
                KEY `bpbi_indicador65_61` (`valor61`),
                KEY `bpbi_indicador65_62` (`valor62`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;

        Yii::$app->db->createCommand($sql_table)->execute();
    }

}
