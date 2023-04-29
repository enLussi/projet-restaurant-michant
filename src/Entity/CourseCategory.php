<?php

namespace App\Entity;

use App\Entity\Trait\LabelTrait;
use App\Repository\CourseCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseCategoryRepository::class)]
class CourseCategory
{

    use LabelTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Course::class)]
    private Collection $courses;

    #[ORM\ManyToMany(targetEntity: SetMenu::class, mappedBy: 'courseCategory')]
    private Collection $setMenus;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->setMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setCategory($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getCategory() === $this) {
                $course->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SetMenu>
     */
    public function getSetMenus(): Collection
    {
        return $this->setMenus;
    }

    public function addSetMenu(SetMenu $setMenu): self
    {
        if (!$this->setMenus->contains($setMenu)) {
            $this->setMenus->add($setMenu);
            $setMenu->addCourseCategory($this);
        }

        return $this;
    }

    public function removeSetMenu(SetMenu $setMenu): self
    {
        if ($this->setMenus->removeElement($setMenu)) {
            $setMenu->removeCourseCategory($this);
        }

        return $this;
    }
}
