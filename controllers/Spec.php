<?php
class Spec extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	// トップページ
	public function index()
	{
		load_template(__FUNCTION__);
	}

	// エンティティ表示
	public function table($actor, $class)
	{
		load_template(__FUNCTION__, ['actor' => $actor, 'class' => $class]);
	}

	// DBからモデルを生成
	public function assemble()
	{
		load_library('db');
		$result = library('db')->query('SELECT * FROM `spec`');
		while (($row = $result->fetch_assoc()))
		{
			// アクター終了
			if ((isset($last_row) && $row['spec_actor'] !== $last_row['spec_actor']) && $last_row['spec_actor'] !== NULL)
			{
				$indent = substr($indent, 0, strlen($indent) - 1);
				echo "{$indent}\$this->actor();\n";
			}

			// テーブル終了
			if (isset($last_row) && $row['spec_table'] !== $last_row['spec_table'])
			{
				echo "\t}\n}\n";
			}

			// テーブル開始
			if ( ! isset($last_row) OR $row['spec_table'] !== $last_row['spec_table'])
			{
				$indent = "\t\t";
				echo "<?php\nclass ".ucfirst($row['spec_table'])."_model extends Crud_model {\n\n\tpublic function __construct(\$params)\n\t{\n\t\tparent::__construct(\$params);\n\n";
			}

			// アクター開始
			if (( ! isset($last_row) OR $row['spec_actor'] !== $last_row['spec_actor']) && $row['spec_actor'] !== NULL)
			{
				echo "\n{$indent}\$this->actor('{$row['spec_actor']}');\n";
				$indent .= "\t";
			}

			switch ($row['spec_method'])
			{
			case 'overwrite':
				switch ($row['spec_key1'])
				{
				case 'primary_key':
				case 'display':
				case 'use_card':
					$value = $row['spec_value'];
					break;
				default:
					show_500("Illigal key for method '{$row['spec_method']}' ('{$row['spec_key1']}')");
					break;
				}
				echo "{$indent}\$this->{$row['spec_method']}('{$row['spec_key1']}', {$value});\n";
				break;
			case 'append':
			case 'remove':
				switch ($row['spec_key1'])
				{
				case 'action_list':
				case 'hidden_list':
				case 'set_list':
				case 'where_list':
				case 'limit_list':
					$value = $row['spec_value'];
					break;
				case 'has_hash':
				case 'row_action_hash':
				case 'select_hash':
				case 'fixed_hash':
				case 'success_hash':
				case 'join_hash':
				case 'where_hash':
				case 'order_by_hash':
					$value = "'{$row['spec_key2']}', {$row['spec_value']}";
					break;
				default:
					show_500("Illigal key for method '{$row['spec_method']}' ('{$row['spec_key1']}')");
					break;
				}
				echo "{$indent}\$this->{$row['spec_method']}('{$row['spec_key1']}', {$value});\n";
				break;
			default:
				show_500("Unknown Method ('{$row['spec_method']}')");
				break;
			}
			$last_row = $row;
		}

		// アクター終了
		if ($last_row['spec_actor'] !== NULL)
		{
			$indent = substr($indent, 0, strlen($indent) - 1);
			echo "{$indent}\$this->actor();\n";
		}

		// テーブル終了
		echo "\t}\n}\n";
	}
}
