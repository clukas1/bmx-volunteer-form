<?php

readonly class Section implements FormItem {

	private string $title;
	private string $description;
	private int $level;

	/**
	 * @var FormItem[]
	 */
	private array $items;

	/**
	 * @var int[]
	 */
	private array $indexes;


	public function __construct(
		array $data,
		int $level,
        ?array $indexes = null,
	) {
		$this->indexes = $indexes ?? [];
		$this->set_title($data['title']);
		$this->set_description($data['description'] ?? '' );
		$this->set_level($level);
		$this->set_items($data['items']);
	}

	public function get_title(): string {
		return $this->title;
	}

	public function get_description(): string {
		return $this->description;
	}

	public function get_level(): int {
		return $this->level;
	}

	public function get_items(): array {
		return $this->items;
	}

	private function set_title(string $title): void {
		$this->title = $title;
	}

	private function set_description(string $description): void {
		$this->description = $description;
	}

	private function set_level(int $level): void {
		if( $level < 1 ){
			throw new InvalidArgumentException('Level must be greater than 0');
		}
		if ( $level > 6 ){
			$level = 6;
		}
		$this->level = $level;
	}

	private function set_items(array $items): void {
		if( empty( $items ) ){
			$this->items = [];
			return;
		}

		$sub_sections = [];
		$form_items = [];

        $index = 0;

		foreach( $items as $item ){
            $new_indexes = $newArray = [...$this->indexes, $index];
            $index++;
			if( isset( $item['title'] ) && isset( $item['items'] ) ){
				$sub_sections[] = new Section( $item, $this->level + 2, $new_indexes );
			}
			else{
				$form_items[] = new Input( $item, $new_indexes );
			}
		}

		if( $sub_sections ){
			$this->items = $sub_sections;
		} else {
			$this->items = $form_items;
		}
	}

	public function render(): void {
		?>
		<div>
			<h<?php echo $this->level; ?>><?php echo $this->title; ?></h<?php echo $this->level; ?>>
			<?php
			if( $this->description ){
				printf( '<p>%s</p>', $this->description );
			}
			?>
			<?php
			foreach( $this->items as $item ){
				$item->render();
			}
			?>
		</div>
		<?php
	}
}